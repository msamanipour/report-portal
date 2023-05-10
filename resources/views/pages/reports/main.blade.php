<div class="container">
    <div class="row mt-5 mb-4">
        <div class="col-lg-2"></div>
        @foreach($users as $user)
            <div class="col-lg-2 text-center">
                <h5 class="mb-4">{{ $user->name }}</h5>
                <img src="{{ asset('images/chart.png') }}" alt="" width="150">
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
