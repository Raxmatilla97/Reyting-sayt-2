<div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('refresh-page', () => {
                location.reload();
            });
        });
    </script>
</div>
