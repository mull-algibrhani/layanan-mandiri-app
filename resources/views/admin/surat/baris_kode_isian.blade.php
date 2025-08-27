@php
    // Handle semua tipe data untuk groupLabel
    if (is_array($groupLabel)) {
        $groupItems = $groupLabel;
        $firstItem = $groupLabel[0] ?? null;
    } elseif (is_object($groupLabel) && method_exists($groupLabel, 'toArray')) {
        // Jika adalah Collection instance
        $groupItems = $groupLabel->toArray();
        $firstItem = $groupItems[0] ?? null;
    } elseif (is_object($groupLabel)) {
        // Jika adalah object biasa
        $groupItems = [$groupLabel];
        $firstItem = $groupLabel;
    } else {
        $groupItems = [];
        $firstItem = null;
    }

    // Handle label properly
    $displayLabel = '';
    if ($firstItem) {
        if (is_array($firstItem)) {
            $displayLabel = $firstItem['nama'] ?? $firstItem['label'] ?? $label ?? '';
        } elseif (is_object($firstItem)) {
            $displayLabel = $firstItem->nama ?? $firstItem->label ?? $label ?? '';
        }
    }

    // Get field name
    $nama = '';
    if ($firstItem) {
        if (is_array($firstItem)) {
            $kode = $firstItem['kode'] ?? '';
        } elseif (is_object($firstItem)) {
            $kode = $firstItem->kode ?? '';
        }
        $nama = str_replace(['[form_', ']'], '', $kode);
    }

    // DECODE isian_form jika masih string JSON
    $isianFormArray = [];
    if (isset($isian_form)) {
        if (is_string($isian_form)) {
            $isianFormArray = json_decode($isian_form, true) ?? [];
        } elseif (is_array($isian_form)) {
            $isianFormArray = $isian_form;
        }
    }

    // Get value - priority: posted data > isian_form > empty
    $value = set_value($nama);
    if (empty($value) && !empty($isianFormArray) && array_key_exists($nama, $isianFormArray)) {
        $value = $isianFormArray[$nama];
    }
@endphp

@if (!empty($groupItems))
<div class="form-group" data-kategori="{{ $keyname ?? '' }}">
    @if (!empty($displayLabel))
    <label for="{{ $nama }}" class="col-sm-3 control-label">{{ $displayLabel }}</label>
    @endif
    <div class="col-sm-9 row">
        @foreach ($groupItems as $item)
            @php
                // Convert item to object for consistent access
                if (is_array($item)) {
                    $item = (object) $item;
                }

                $fieldName = str_replace(['[form_', ']'], '', $item->kode);
                $class = buat_class($item->atribut, '', $item->required);
                $widthClass = $item->kolom ? 'col-sm-' . $item->kolom : 'col-sm-12';
                $dataKaitkan = strlen($item->kaitkan_kode ?? '') > 10 ? "data-kaitkan='" . $item->kaitkan_kode . "'" : '';

                // Get value for this specific field
                $fieldValue = set_value($fieldName);
                if (empty($fieldValue) && !empty($isianFormArray) && array_key_exists($fieldName, $isianFormArray)) {
                    $fieldValue = $isianFormArray[$fieldName];
                }
            @endphp

            @if ($item->tipe == 'select-manual')
                <div class="{{ $widthClass }}">
                    <select name="{{ $fieldName }}" {!! $class !!} {!! $dataKaitkan !!}>
                        <option value="">-- {{ $item->deskripsi }} --</option>
                        @foreach ($item->pilihan as $key => $pilih)
                            <option @selected($fieldValue == $pilih) value="{{ $pilih }}">{{ $pilih }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($item->tipe == 'select-otomatis')
                <div class="{{ $widthClass }}">
                    <select name="{{ $fieldName }}" {!! $class !!} placeholder="{{ $item->deskripsi }}">
                        <option value="">-- {{ $item->deskripsi }} --</option>
                        @foreach (ref($item->refrensi) as $key => $pilih)
                            <option @selected($fieldValue == $pilih->nama) value="{{ $pilih->nama }}">{{ $pilih->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif ($item->tipe == 'textarea')
                <div class="{{ $widthClass }}">
                    <textarea name="{{ $fieldName }}" {!! $class !!} placeholder="{{ $item->deskripsi }}">{{ $fieldValue }}</textarea>
                </div>
            @elseif ($item->tipe == 'date' || $item->tipe == 'hari' || $item->tipe == 'hari-tanggal')
                <div class="{{ $widthClass }}">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" @if (strpos($item->atribut, 'datepicker') !== false) {!! buat_class($item->atribut, 'form-control input-sm', $item->required) !!} @else {!! buat_class($item->atribut, 'form-control input-sm tgl', $item->required) !!} @endif name="{{ $fieldName }}" placeholder="{{ $item->deskripsi }}" value="{{ $fieldValue }}" />
                    </div>
                </div>
            @elseif ($item->tipe == 'time')
                <div class="{{ $widthClass }}">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" {!! buat_class($item->atribut, 'form-control input-sm jam', $item->required) !!} name="{{ $fieldName }}" placeholder="{{ $item->deskripsi }}" value="{{ $fieldValue }}" />
                    </div>
                </div>
            @elseif ($item->tipe == 'datetime')
                <div class="{{ $widthClass }}">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" {!! buat_class($item->atribut, 'form-control input-sm tgl_jam', $item->required) !!} name="{{ $fieldName }}" placeholder="{{ $item->deskripsi }}" value="{{ $fieldValue }}" />
                    </div>
                </div>
            @else
                <div class="{{ $widthClass }}" {!! count($groupItems) > 2 ? 'style="margin-bottom: 10px"' : '' !!}>
                    <input type="{{ $item->tipe }}" {!! $class !!} name="{{ $fieldName }}"
                           placeholder="{{ $item->deskripsi }}" value="{{ $fieldValue }}" />
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif
