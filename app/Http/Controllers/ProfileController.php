<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user->is_admin) {
            $departments = Department::all();
        } else {
            $departments = Department::where('id', '!=', 1)->get();
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'departments' => $departments,
        ]);
    }

    public function updateDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = $request->user();
        $oldDepartmentId = $user->department_id;
        $newDepartmentId = $request->department_id;

        DB::transaction(function () use ($user, $oldDepartmentId, $newDepartmentId) {
            // Update user's department
            $user->department_id = $newDepartmentId;
            $user->save();

            // Update related records in point_user_deportaments
            PointUserDeportament::where('user_id', $user->id)
                ->where('departament_id', $oldDepartmentId)
                ->update(['departament_id' => $newDepartmentId]);
        });

        return Redirect::route('profile.edit')->with('status', 'department-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($user->image) {
                Storage::delete('public/users/image/' . $user->image);
            }

            // Store new image
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('users/image', $fileName, 'public');
            $validatedData['image'] = $fileName;
        }

        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
