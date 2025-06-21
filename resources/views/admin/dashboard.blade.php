@extends('layouts.app')
@section('title','Dashboard Stock Opname PLTGU Tanjung Uncang')
@section('content')



<div class="row mt-2">
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Barang Elektrik</h5>
                <canvas id="totalBarangChart" style="width: 100%; height: 400px;"></canvas>
    
                <!-- Daftar 5 Barang Terendah -->
                <div class="mt-4">
                    <h6>Stok Barang Elektrik Terendah</h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowestItemsElectric as $item)
                                <tr>
                                    <td>{{ $item->part_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Barang Mekanik</h5>
                <canvas id="totalBarangMekanikChart" style="width: 100%; height: 400px;"></canvas>

                <!-- Daftar 5 Barang Terendah -->
                <div class="mt-4">
                    <h6>Stok Barang Mekanik Terendah</h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowestItemsMechanical as $item)
                                <tr>
                                    <td>{{ $item->part_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Barang Elektrik Quantity</h5>
                <canvas id="doughnutChart" style="width: 100%; height: 400px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Barang Mekanik Quantity</h5>
                <canvas id="doughnutMekanikChart" style="width: 100%; height: 400px;"></canvas>
            </div>
        </div>
    </div>
  
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transaksi Barang Elektrik</h5>
                <canvas id="stockChart" style="width: 100%; height: 400px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transaksi Barang Mekanik</h5>
                <canvas id="stockMekanikChart" style="width: 100%; height: 400px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transaksi Barang Kimia</h5>
                <canvas id="stockKimiaChart" style="width: 100%; height: 400px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data User</h5>
                <canvas id="statusChart" style="width: 100%; height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('theme/plugins/chart.js/Chart.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Embed PHP variables for active and inactive counts into JavaScript
    const activeCount = {{ $activeCount }};
    const inactiveCount = {{ $inactiveCount }};

    // User Status Chart
    const userStatusData = {
        labels: ['Active', 'Inactive'],
        datasets: [{
            label: 'User Status',
            data: [activeCount, inactiveCount],
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgb(255, 159, 64)',
                'rgb(255, 99, 132)'
            ],
            borderWidth: 1
        }]
    };

    const statusChartConfig = {
        type: 'doughnut',
        data: userStatusData,
        options: {
            responsive: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    enabled: true,
                }
            }
        }
    };

    // Initialize the status chart
    const statusChart = new Chart(
        document.getElementById('statusChart'),
        statusChartConfig
    );
</script>



<script>
    // Data untuk Doughnut Chart
    var ctx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctx, {
    type: 'doughnut',  // Tipe chart
    data: {
        labels: ['Active', 'Inactive'],  // Label untuk status
        datasets: [{
            label: 'User Status',
            data: [{{ $activeCount }}, {{ $inactiveCount }}],  // Data jumlah pengguna aktif dan tidak aktif
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)', // Warna untuk Active
                'rgba(153, 102, 255, 0.2)'   // Warna untuk Inactive
            ],
            borderColor: [
                'rgb(255, 159, 64)',  // Border warna untuk Active
                'rgb(153, 102, 255)'   // Border warna untuk Inactive
            ],
            borderWidth: 1,  // Mengatur ketebalan border
        }]
    },
    options: {
        responsive: true,
    }
});

</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const sparepartItemsData = @json($sparepart_items_data); // Pass data from Laravel to JS

      const labels = sparepartItemsData.map(item => item.part_name); // Get part names
      const quantities = sparepartItemsData.map(item => item.quantity); // Get quantities

      const data = {
          labels: labels,
          datasets: [{
              label: 'Sparepart',
              data: quantities,
              backgroundColor:  'rgba(153, 102, 255, 0.2)', // Red color
              borderColor: 'rgb(153, 102, 255)', // Red color
              borderWidth: 1
          }]
      };

      const config = {
          type: 'bar',
          data: data,
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top',
                  },
                  tooltip: {
                      enabled: true
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          },
      };

      // Create the bar chart for consumable items
      var damagedItemsChart = new Chart(
          document.getElementById('sparepartItemsChart'),
          config
      );
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const damagedItemsData = @json($damaged_items_data); // Pass data from Laravel to JS

      const labels = damagedItemsData.map(item => item.part_name); // Get part names
      const quantities = damagedItemsData.map(item => item.quantity); // Get quantities

      const data = {
          labels: labels,
          datasets: [{
              label: 'Damaged Items Quantity',
              data: quantities,
              backgroundColor: 'rgba(75, 192, 192, 0.2)', // Red color
              borderColor: 'rgb(75, 192, 192)', // Red color
              borderWidth: 1
          }]
      };

      const config = {
          type: 'bar',
          data: data,
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top',
                  },
                  tooltip: {
                      enabled: true
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          },
      };

      // Create the bar chart for consumable items
      var damagedItemsChart = new Chart(
          document.getElementById('damagedItemsChart'),
          config
      );
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const consumableItemsData = @json($consumable_items_data); // Pass data from Laravel to JS

      const labels = consumableItemsData.map(item => item.part_name); // Get part names
      const quantities = consumableItemsData.map(item => item.quantity); // Get quantities

      const data = {
          labels: labels,
          datasets: [{
              label: 'Consumable Items Quantity',
              data: quantities,
              backgroundColor: 'rgba(54, 162, 235, 0.2)', // Blue color
              borderColor: 'rgb(54, 162, 235)', // Blue color
              borderWidth: 1
          }]
      };

      const config = {
          type: 'bar',
          data: data,
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top',
                  },
                  tooltip: {
                      enabled: true
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          },
      };

      // Create the bar chart for consumable items
      var consumableItemsChart = new Chart(
          document.getElementById('consumableItemsChart'),
          config
      );
  });
</script>


<script>
    const data = {
        labels: ['Barang Elektrik', 'Barang Bekas Pakai', 'Barang Rusak'], // Tiga label kategori
        datasets: [{
            label: 'Total Barang', // Label utama untuk dataset
            data: [{{ $product_count }}, {{ $consumable_item }}, {{ $damaged_item }}], // Data dinamis
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)', // Warna untuk Stok Sparepart
                'rgba(75, 192, 192, 0.2)', // Warna untuk Bekas Pemakaian
                'rgba(153, 102, 255, 0.2)'  // Warna untuk Kerusakan Barang
            ],
            borderColor: [
                'rgb(54, 162, 235)', // Warna border Stok Sparepart
                'rgb(75, 192, 192)', // Warna border Bekas Pemakaian
                'rgb(153, 102, 255)'  // Warna border Kerusakan Barang
            ],
            borderWidth: 1
        }]
    };
  
    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true, // Tampilkan legenda
                    labels: {
                        // Label hanya mencantumkan nama kategori
                        generateLabels: function(chart) {
                            return chart.data.labels.map(function(label, index) {
                                return {
                                    text: label,
                                    fillStyle: chart.data.datasets[0].backgroundColor[index],
                                    strokeStyle: chart.data.datasets[0].borderColor[index],
                                    lineWidth: chart.data.datasets[0].borderWidth
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    enabled: true // Aktifkan tooltip
                }
            },
            scales: {
                y: {
                    beginAtZero: true // Grafik dimulai dari 0
                }
            }
        },
    };
  
    // Create the bar chart
    var totalBarangChart = new Chart(
        document.getElementById('totalBarangChart'),
        config
    );
  </script>
  
  <script>
const dataMekanik = {
    labels: ['Barang Mekanik', 'Barang Bekas Pakai', 'Barang Rusak'],
    datasets: [{
        label: 'Total Barang Mekanik',
        data: [{{ $product_count_mechanical }}, {{ $consumable_item_mechanical }}, {{ $damaged_item_mechanical }}],
        backgroundColor: [
            'rgba(54, 162, 235, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)'
        ],
        borderColor: [
            'rgb(54, 162, 235)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)'
        ],
        borderWidth: 1
    }]
};

const configMekanik = {
    type: 'bar',
    data: dataMekanik,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                labels: {
                    generateLabels: function(chart) {
                        return chart.data.labels.map(function(label, index) {
                            return {
                                text: label,
                                fillStyle: chart.data.datasets[0].backgroundColor[index],
                                strokeStyle: chart.data.datasets[0].borderColor[index],
                                lineWidth: chart.data.datasets[0].borderWidth
                            };
                        });
                    }
                }
            },
            tooltip: {
                enabled: true
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

var totalBarangMekanikChart = new Chart(
    document.getElementById('totalBarangMekanikChart'),
    configMekanik
);

    
  </script>

<script>
    //doughnutChart total_consumable_items, total_damaged_items
    const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
    const doughnutChart = new Chart(doughnutCtx, {
        type: 'doughnut', // Ganti tipe grafik menjadi 'doughnut'
        data: {
            labels: ['Barang Sparepart', 'Barang Bekas Pakai', 'Barang Rusak'], // Tiga label utama
            datasets: [{
                label: 'Status Barang', // Judul data
                data: [{{ $sumItem }}, {{ $sumConsumableItems }}, {{ $sumDamagedItems }}], // Data nilai
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)', // Barang Sparepart
                    'rgba(255, 159, 64, 0.2)', // Barang Bekas Pakai
                    'rgba(255, 99, 132, 0.2)'  // Barang Rusak
                ],
                borderColor: [
                    'rgb(54, 162, 235)', // Barang Sparepart
                    'rgb(255, 159, 64)', // Barang Bekas Pakai
                    'rgb(255, 99, 132)'  // Barang Rusak
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    display: true, // Tampilkan legenda
                    position: 'top' // Posisi legenda
                },
                tooltip: {
                    enabled: true // Aktifkan tooltip saat hover
                }
            }
        }
    });


</script>

<script>
    //doughnutChart total_consumable_items, total_damaged_items
    const doughnutCtxMechanical = document.getElementById('doughnutMekanikChart').getContext('2d');
const doughnutMekanikChart = new Chart(doughnutCtxMechanical, {
    type: 'doughnut', // Ganti tipe grafik menjadi 'doughnut'
    data: {
        labels: ['Barang Sparepart', 'Barang Bekas Pakai', 'Barang Rusak'], // Tiga label utama
        datasets: [{
            label: 'Status Barang', // Judul data
            data: [{{ $sumItemMechanical }}, {{ $sumConsumableItemsMechanical }}, {{ $sumDamagedItemsMechanical }}], // Data nilai
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)', // Barang Sparepart
                'rgba(255, 159, 64, 0.2)', // Barang Bekas Pakai
                'rgba(255, 99, 132, 0.2)'  // Barang Rusak
            ],
            borderColor: [
                'rgb(54, 162, 235)', // Barang Sparepart
                'rgb(255, 159, 64)', // Barang Bekas Pakai
                'rgb(255, 99, 132)'  // Barang Rusak
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: false,
        plugins: {
            legend: {
                display: true, // Tampilkan legenda
                position: 'top' // Posisi legenda
            },
            tooltip: {
                enabled: true // Aktifkan tooltip saat hover
            }
        }
    }
});


</script>

<script>
    // stockChart goods_in_this_month, goods_out_this_month, total_stock_this_month
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(stockCtx, {
        type: 'bar',
        data: {
            labels: ['Transaksi Masuk', 'Transaksi Keluar', 'Total Stock'], // Labels for the X-axis
            datasets: [{
                label: 'Stock Data', // Dataset label
                data: [{{ $goodsInSum }}, {{ $goodsOutSum }}, {{ $sumItem }}], // Dynamic data values from backend
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Color for 'Transaksi Masuk'
                    'rgba(54, 162, 235, 0.2)', // Color for 'Transaksi Keluar'
                    'rgba(153, 102, 255, 0.2)'  // Color for 'Total Stock'
                ],
                borderColor: [
                    'rgb(75, 192, 192)', // Border color for 'Transaksi Masuk'
                    'rgb(54, 162, 235)', // Border color for 'Transaksi Keluar'
                    'rgb(153, 102, 255)'  // Border color for 'Total Stock'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        generateLabels: function(chart) {
                            return chart.data.labels.map(function(label, index) {
                                return {
                                    text: label,
                                    fillStyle: chart.data.datasets[0].backgroundColor[index],
                                    strokeStyle: chart.data.datasets[0].borderColor[index],
                                    lineWidth: chart.data.datasets[0].borderWidth
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    enabled: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true // Ensure the Y-axis starts at 0
                }
            }
        }
    });
</script>



<script>
    // stockMekanikChart goods_in_this_month, goods_out_this_month, total_stock_this_month
    const stockCtxMekanik = document.getElementById('stockMekanikChart').getContext('2d');
    const stockMekanikChart = new Chart(stockCtxMekanik, {
        type: 'bar',
        data: {
            labels: ['Transaksi Masuk', 'Transaksi Keluar', 'Total Stock'], // Labels for X-axis
            datasets: [{
                label: 'Stock Data', // Label for the dataset
                data: [{{ $goodsInSumMechanical }}, {{ $goodsOutSumMechanical }}, {{ $sumItemMechanical }}], // Dynamic values from backend
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Color for 'Transaksi Masuk'
                    'rgba(54, 162, 235, 0.2)', // Color for 'Transaksi Keluar'
                    'rgba(153, 102, 255, 0.2)'  // Color for 'Total Stock'
                ],
                borderColor: [
                    'rgb(75, 192, 192)', // Border color for 'Transaksi Masuk'
                    'rgb(54, 162, 235)', // Border color for 'Transaksi Keluar'
                    'rgb(153, 102, 255)'  // Border color for 'Total Stock'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true, // Show legend
                    labels: {
                        generateLabels: function(chart) {
                            return chart.data.labels.map(function(label, index) {
                                return {
                                    text: label,
                                    fillStyle: chart.data.datasets[0].backgroundColor[index],
                                    strokeStyle: chart.data.datasets[0].borderColor[index],
                                    lineWidth: chart.data.datasets[0].borderWidth
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    enabled: true // Enable tooltips
                }
            },
            scales: {
                y: {
                    beginAtZero: true // Ensure the Y-axis starts from zero
                }
            }
        }
    });
</script>



<script>
    // stockKimiaChart goods_in_this_month, goods_out_this_month, total_stock_this_month
    const stockCtxKimia = document.getElementById('stockKimiaChart').getContext('2d');
    const stockKimiaChart = new Chart(stockCtxKimia, {
        type: 'bar',
        data: {
            labels: ['Transaksi Masuk', 'Transaksi Keluar', 'Total Stock'], // Labels for X-axis
            datasets: [{
                label: 'Stock Data', // Label for the dataset
                data: [{{ $goodsInSumChemical }}, {{ $goodsOutSumChemical }}, {{ $sumItemChemical }}], // Dynamic values from backend
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Color for 'Transaksi Masuk'
                    'rgba(54, 162, 235, 0.2)', // Color for 'Transaksi Keluar'
                    'rgba(153, 102, 255, 0.2)'  // Color for 'Total Stock'
                ],
                borderColor: [
                    'rgb(75, 192, 192)', // Border color for 'Transaksi Masuk'
                    'rgb(54, 162, 235)', // Border color for 'Transaksi Keluar'
                    'rgb(153, 102, 255)'  // Border color for 'Total Stock'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true, // Show legend
                    labels: {
                        generateLabels: function(chart) {
                            return chart.data.labels.map(function(label, index) {
                                return {
                                    text: label,
                                    fillStyle: chart.data.datasets[0].backgroundColor[index],
                                    strokeStyle: chart.data.datasets[0].borderColor[index],
                                    lineWidth: chart.data.datasets[0].borderWidth
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    enabled: true // Enable tooltips
                }
            },
            scales: {
                y: {
                    beginAtZero: true // Ensure the Y-axis starts from zero
                }
            }
        }
    });
</script>


@endsection
