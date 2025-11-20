@extends('templates.app')

{{-- mengisi link yield --}}
@section('content')

   <div class="container mt-5 my-2">
      <h5>Grafik Pembelian Tiket</h5>
      <div class="row">
         <div class="col-6">
            <h5>Pembelian tiken bulan {{ now()->format('F') }}</h5>
            <canvas id="chartBar"></canvas>
         </div>
         <div class="col-6">
            <h5>Perbandingan film aktif dan non aktif</h5>
            <canvas id="chartPie"></canvas>
         </div>
      </div>
   </div>

@endsection

@push('script')
   <script>
      let labelChartBar = null;
      let dataChartBar = null;
      let dataChartPie = null;

      // dijalankan ketika browser sudah generate kode html nya (pas di refresh)
      $(function () {
         $.ajax({
            url: "{{ route('admin.tickets.chart') }}",
            method: "GET",
            success: function (response) {
               labelChartBar = response.labels;
               dataChartBar = response.data;
               // panggil function untuk munculkan grafik nya
               showChart();
            },
            error: function (err) {
               alert('Gagal mengambil data untuk chart tiket!');
            }
         });
         $.ajax({
            url: "{{ route('admin.movies.chart') }}",
            method: "GET",
            success: function (response) {
               dataChartPie = response.data;
               showChartPie();
            },
            error: function (err) {
               alert('Gagal mengambil data untuk chart film!');
            }
         })
      });

      function showChart() {
         const ctx = document.getElementById('chartBar');

         new Chart(ctx, {
            type: 'bar',
            data: {
               labels: labelChartBar,
               datasets: [{
                  label: 'Pembelian tiket bulan ini',
                  data: dataChartBar,
                  borderWidth: 1
               }]
            },
            options: {
               scales: {
                  y: {
                     beginAtZero: true
                  }
               }
            }
         });
      }

      function showChartPie() {
         const ctx2 = document.getElementById('chartPie');

         new Chart(ctx2, {
            type: 'pie',
            data: {
               labels: ['Film aktif', 'Film tidak aktif'],
               datasets: [{
                  label: 'Data film',
                  data: dataChartPie,
                  backgroundColor: [
                     'rgb(255, 99, 132)',
                     'rgb(54, 162, 235)',
                  ],
                  hoverOffset: 4
               }]
            }
         });
      }
   </script>
@endpush