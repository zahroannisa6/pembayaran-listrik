@extends('layouts.admin')

@section('title', 'Dashboard')

@push('addon-style')
<script src="{{ asset('assets/plugin/Chart.js-3.0.2/chart.min.js') }}" charset="utf-8"></script>
@endpush

@section('content')
  <div class="container-fluid">
    <div class="row justify-content-center">
      <!-- Total Revenue Overview -->
      @if (auth()->user()->isAdmin())
        <div class="col-12 col-md-6 col-lg-3 mb-3">
          <div class="card card-overview">
            <div class="card-body">
              <div class="row align-items-center h-100">
                <div class="col-4">
                  <img src="{{ asset('assets/img/mm-icon/revenue-icon@2x.png') }}" alt="Payment Icon" width="65" height="65">
                </div>
                <div class="col-8">
                  <h6 class="font-weight-bold">{{$totalPendapatan}}</h6>
                  {{-- @if ($monthEarnings > 0)
                    <h6 class="text-success font-weight-bold">+ {{$monthEarnings}} Bulan ini</h6>
                  @endif --}}
                  Total Pendapatan
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
      <!-- End of Total Revenue Overview -->

      <!-- Total Payment Overview -->
      @if (auth()->user()->isAdmin())
        <div class="col-12 col-md-6 col-lg-3 mb-3">
          <div class="card card-overview">
            <div class="card-body">
              <div class="row align-items-center h-100">
                <div class="col-4">
                  <img src="{{ asset('assets/img/mm-icon/payment-icon@2x.png') }}" alt="Payment Icon" width="65" height="65">
                </div>
                <div class="col-8">
                  <h6 class="font-weight-bold">{{$payments->count()}}</h6>
                  Total pembayaran
                </div>
              </div>
            </div>
          </div>
        </div>
      @else
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <div class="card card-overview">
            <div class="card-body">
              <div class="row align-items-center h-100">
                <div class="col-4">
                  <img src="{{ asset('assets/img/mm-icon/payment-icon@2x.png') }}" alt="Payment Icon" width="65" height="65">
                </div>
                <div class="col-8">
                  <h6 class="font-weight-bold">{{$payments->count()}}</h6>
                  <div>Total pembayaran</div>
                  <a href="{{route('admin.payments.index')}}" class="text-decoration-none">Lihat detail pembayaran</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
      <!-- End of Total Payment Overview -->

      <!-- Bill Paid Off Overview -->
      @if(auth()->user()->isAdmin())
        <div class="col-12 col-md-6 col-lg-3 mb-3">
          <div class="card card-overview">
            <div class="card-body">
              <div class="row align-items-center h-100">
                <div class="col-4">
                  <img src="{{ asset('assets/img/mm-icon/bill-paid-off-icon@2x.png') }}" alt="Payment Icon" width="65" height="65">
                </div>
                <div class="col-8">
                  <h6 class="font-weight-bold">{{$bills->where('status', 'LUNAS')->count()}}</h6>
                  Tagihan listrik lunas
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
      <!-- End of Bill Paid Off Overview -->

      <!-- Bill Not Paid Off Overview -->
      @if(auth()->user()->isAdmin())
        <div class="col-12 col-md-6 col-lg-3 mb-3">
          <div class="card card-overview">
            <div class="card-body">
              <div class="row align-items-center h-100">
                <div class="col-4">
                  <img src="{{ asset('assets/img/mm-icon/bill-not-paid-off-icon@2x.png') }}" alt="Payment Icon" width="65" height="65">
                </div>
                <div class="col-8">
                  <h6 class="font-weight-bold">{{$bills->where('status', 'BELUM LUNAS')->count()}}</h6>
                  Tagihan listrik belum lunas
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      {{-- Grafik pendapatan tahunan --}}
      @if (auth()->user()->isAdmin())
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              {!! $chart->container() !!}
            </div>
          </div>
        </div>
      @endif
      <!-- End of Bill Not Paid Off Overview -->
      <div class="col-12">
        <h2 class="text-center mt-5">Histori Pembayaran</h2>
        <div class="card my-5 ">
          <div class="card-body table-responsive">
            <table class="table table-striped table-bordered w-100" id="paymentHistories">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama Customer</th>
                  <th>Nama Pelanggan PLN</th>
                  <th>ID Tagihan</th>
                  <th>Tanggal Bayar</th>
                  <th>Biaya Admin</th>
                  <th>Denda</th>
                  <th>Total Bayar</th>
                  <th>Metode Pembayaran</th>
                  <th>Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('addon-script')
  {!! $chart->script() !!}
  <script>
    $('#paymentHistories').DataTable({
        responsive: true,
        serverSide: true,
        ajax: "",
        columns: [
            {data: 'id'},
            {data: 'nama'},
            {data: 'nama_pelanggan'},
            {data: 'id_tagihan'},
            {data: 'tanggal_bayar'},
            {data: 'biaya_admin',
             render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {data: 'denda',
             render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {data: 'total_bayar',
             render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
            },
            {data: 'payment.payment_method.nama', defaultContent: '-'},
            {data: 'status',
              render: function(data, type, row){
                let state;
                if(data == 'success'){
                  state = 'success';
                }else if(data == 'pending'){
                  state = 'warning';
                }else{
                  state = 'danger';
                }
                return `<span class='badge badge-pill 
                        badge-${state}'>${data}</span>`;
              }
            },
        ]
    });
  </script>
@endpush