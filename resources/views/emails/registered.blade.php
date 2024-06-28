<x-mail::table>
    | Data         | Keterangan      |
    | ---------:   |:----------:     |
    | Penerima     | {{$reciever}}   |
    | Email        | {{$email}}      |
    | Event        | {{$eventTitle}} |
    | Lokasi       | {{$eventLocation}} |
    | Mulai Pada   | {{$start_time}} |
    | Selesai Pada | {{$end_time}}   |
</x-mail::table>
<x-mail::button :url="$qr_img">
Lihat Qr Code
</x-mail::button>