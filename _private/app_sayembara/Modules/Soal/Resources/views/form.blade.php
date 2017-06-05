<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"> {{ val($dataForm, 'form_title') }} </h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                
                <form method="POST" action="{{ BeUrl(config('soal.info.alias').'/save') }}" id="formData">
                
                <div class="form-group has-feedback">
                    <input type="checkbox" name="status" {{ isset($dataForm['status']) ? (val($dataForm, 'status')=='1' ? 'checked' : '') : 'checked' }} /> {{ trans('global.status_active') }}
                </div>

                <div class="form-group has-feedback">
                    <label>{{ trans('soal::global.kategori') }}</label><span class="char_count"></span>
                    <select name="kategori_id" class="select2 form-control">
                    <option value=0>-</option>
                    @foreach($categories as $cID=>$cName)
                        <option value="{{$cID}}" {{ val($dataForm, 'kategori_id')==$cID ? 'selected' : '' }}>{{$cName}}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group has-feedback">
                    <label>{{ trans('soal::global.soal') }}</label><span class="char_count"></span>
                    <input type="text" class="form-control" name="soal" maxlength="255" value="{{ val($dataForm, 'soal') }}" />
                </div>

                <div class="form-group has-feedback">
                    <label>{{ trans('soal::global.pilihan') }} & {{ trans('soal::global.jawaban') }}</label>
                    <table class="table table-bordered table-striped table-hover">
                    <?php $pilihan = json_decode(val($dataForm, 'pilihan'), true); ?>
                    @for($i=0; $i<5;$i++)
                    <tr>
                        <td style="width: 10px;padding-top: 15px;"><input type="radio" name="pilihan[jawaban]" value="{{toAlpha($i)}}" {{ val($pilihan, 'jawaban')==toAlpha($i) ? 'checked' : '' }}></td>
                        <td><input type="text" class="form-control" name="pilihan[soal][{{toAlpha($i)}}]"  maxlength="255" value="{{ val($pilihan['soal'], toAlpha($i)) }}" /></td>
                    </tr>
                    @endfor
                    </table>
                </div>


                <input type="hidden" name="id" value="{{ val($dataForm, 'id') }}" />
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-primary btn-flat">{{ val($dataForm, 'id') ? trans('global.act_edit') : trans('global.act_add') }}</button>
                <a href="{{ BeUrl(config('soal.info.alias').'/edit/0') }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_back') }}</a>
                </form>
                
            </div><!-- /.box-body -->
        </div>
    </div>
</div>