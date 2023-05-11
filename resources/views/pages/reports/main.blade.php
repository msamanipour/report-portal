<div class="container">
    <div class="row mt-5 mb-4">
        <div class="col-lg-2"></div>
        @foreach($users as $user)
            <div class="col-lg-2 text-center" wire:ignore>
                <h5 class="mb-4">{{ $user->name }}</h5>
                <div id="chart{{ $user->id }}"></div>
                <script>
                    var options = {
                        series: ['{{ ($this->chart[$user->id] ?? '0') }}'],
                        chart: {
                            type: 'radialBar',
                            offsetY: -20,
                            sparkline: {
                                enabled: true
                            }
                        },
                        plotOptions: {
                            radialBar: {
                                startAngle: -90,
                                endAngle: 90,
                                track: {
                                    background: "#e7e7e7",
                                    strokeWidth: '97%',
                                    margin: 5,
                                    dropShadow: {
                                        enabled: true,
                                        top: 2,
                                        left: 0,
                                        color: '#999',
                                        opacity: 1,
                                        blur: 2
                                    }
                                },
                                dataLabels: {
                                    name: {
                                        show: false
                                    },
                                    value: {
                                        offsetY: -2,
                                        fontSize: '22px'
                                    }
                                }
                            }
                        },
                        grid: {
                            padding: {
                                top: -10
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'light',
                                shadeIntensity: 0.4,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 1,
                                stops: [0, 50, 53, 91]
                            },
                        },
                        labels: ['Average Results'],
                    };
                    var chart{{ $user->id }} = new ApexCharts(document.querySelector("#chart" + {{ $user->id }}), options);
                    chart{{ $user->id }}.render();
                    window.addEventListener('contentChanged', event => {
                        // console.log(event.detail.newName['1']);
                        var val;
                        if (event.detail.newName['{{ $user->id }}']) {
                            val = event.detail.newName['{{ $user->id }}'];
                        } else {
                            val = 0;
                        }
                        chart{{ $user->id }}.updateSeries([val]);
                    });
                </script>
            </div>
        @endforeach
    </div>
    @foreach($times as $key => $time)
        @php
            $checkTime = \Carbon\Carbon::now()->format('H:i') > $time->time;
        @endphp
        <div class="row mb-5">
            <div class="col-lg-2">
                <span class="text-secondary">{{ $time->time }}</span>
            </div>
            @foreach($users as $user)
                @php
                    $val = (isset($result[$user->id][$time->id]) ? $result[$user->id][$time->id] : false);
                @endphp
                <div class="col-lg-2 text-center">
                    @if(!empty($time->time))
                        <input type="text"
                               class="form-control rounded-5 inset-shadow {{ ($checkTime && empty($val['body'])) ? 'bg-soft-danger is-invalid' : ((!empty($val['body'])) ? 'is-valid' : '') }} "
                               {{ ($checkTime || \Carbon\Carbon::now()->format('H:i') < '09:00') ? 'disabled' : '' }} value="{{ ($val) ? $val['body'] : '' }}"
                               wire:change="changeReport" wire:model="result.{{ $user->id }}.{{ $time->id }}.body">
                    @else
                        <textarea
                            class="form-control rounded-5 inset-shadow {{ ((!empty($val['body'])) ? 'is-valid' : '') }} "
                            value="{{ ($val) ? $val['body'] : '' }}"
                            wire:change="changeReport"
                            {{ (\Carbon\Carbon::now()->format('H:i') < '09:00') ? 'disabled' : '' }}
                            wire:model="result.{{ $user->id }}.{{ $time->id }}.body"></textarea>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>
