<div class="detail-box">
    <div class="detail-box-header">
        <p>📋 Detail Perubahan</p>
    </div>
    <table class="detail-table">
        <tbody>
            @foreach ($rows as [$label, $value])
                <tr class="detail-row">
                    <td class="detail-label">{{ $label }}</td>
                    <td class="detail-value">{{ $value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
