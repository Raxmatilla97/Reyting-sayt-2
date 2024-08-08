<div>
    <!-- Display item details here -->
    <p><strong>Name:</strong> {{ $item->employee->FullName }}</p>
    <p><strong>Status:</strong> {{ $item->status }}</p>
    <p><strong>Points:</strong> {{ $item->points }}</p>
    <p><strong>Created at:</strong> {{ $item->created_at }}</p>
    <!-- Add more fields as necessary -->
</div>
