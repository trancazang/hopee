
<time x-data="timestamp" 
      x-init="setNaturalDiff('{{ $carbon }}')" 
      class="timestamp"
      datetime="{{ $carbon }}"
      title="{{ $carbon->toDayDateTimeString() }}">
    <span x-text="naturalDiff"></span>
</time>

@script
<script>
    Alpine.data('timestamp', () => {
        return {
            naturalDiff: '',
            setNaturalDiff(thenStr) {
                const then = new Date(thenStr);
                const now = new Date();
                const diff = Math.floor((now - then) / 1000); // in seconds

                if (diff < 60) {
                    this.naturalDiff = 'vài giây trước';
                } else if (diff < 3600) {
                    const minutes = Math.floor(diff / 60);
                    this.naturalDiff = `${minutes} phút trước`;
                } else if (diff < 86400) {
                    const hours = Math.floor(diff / 3600);
                    this.naturalDiff = `${hours} giờ trước`;
                } else if (diff < 2592000) {
                    const days = Math.floor(diff / 86400);
                    this.naturalDiff = `${days} ngày trước`;
                } else {
                    // Trả về định dạng ngày đơn giản nếu quá cũ
                    this.naturalDiff = then.toLocaleDateString('vi-VN', {
                        day: '2-digit', month: '2-digit', year: 'numeric'
                    });
                }
            }
        }
    });
</script>
@endscript
