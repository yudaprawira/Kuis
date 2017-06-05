@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Helper</h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-striped table-bordered" id="list-module">
                    <thead>
                    <tr>
                      <th class="col-md-3">Syntax</th>
                      <th class="col-md-5">Description</th>
                      <th class="col-md-4">Example</th>
                    </tr>
                    </thead>
                    <tbody>
                      
                      <tr>
                        <td>
                          <sc>BeUrl($path='')</sc>
                        </td>
                        <td>
                          Get path URL for backend
                        </td>
                        <td>
                          <sc>BeUrl('page')</sc>
                          result : {{ BeUrl('page') }}
                        </td>
                      </tr>

                      
                      <tr>
                        <td>
                          <sc>echoPre($array)</sc>
                        </td>
                        <td>
                          Debug Array / Object
                        </td>
                        <td>
                          <sc>echoPre(['a'=>'Apple', 'b'=>'Banana'])</sc>
                          result : {{ echoPre(['a'=>'Apple', 'b'=>'Banana']) }}
                        </td>
                      </tr> 

                      <tr>
                        <td>
                          <sc>createLink($url, $title, $target='_blank', $class='')</sc>
                        </td>
                        <td>
                          Create link
                          <ul>
                            <li> $url : link url, target landingpage </li>
                            <li> $title : caption </li>
                            <li> $target : <b>_self</b> to open in same tab or <b>_blank</b> to open in new tab  </li>
                            <li> $class : styling caption  </li>
                          </ul>
                        </td>
                        <td>
                          <sc>createLink('http://google.com', 'link to google', '_blank', 'text-danger')</sc>
                          result : {!! createLink('http://google.com', 'link to google', '_blank', 'text-danger') !!}
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <sc>linkable($text, $link='', $target='_self', $class='')</sc>
                        </td>
                        <td>
                          Create link or only text
                          <ul>
                            <li> $link : link url, target landingpage </li>
                            <li> $text : caption </li>
                            <li> $target : <b>_self</b> to open in same tab or <b>_blank</b> to open in new tab  </li>
                            <li> $class : styling caption  </li>
                          </ul>
                        </td>
                        <td>
                          <sc>linkable('link to google', 'http://google.com')</sc>
                          result : {!! linkable('link to google', 'http://google.com') !!}
                          <br/><br/>
                          <sc>linkable('link to google', '')</sc>
                          result : {!! linkable('link to google', '') !!}
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <sc>setLog($activity, $link='', $userID='')</sc>
                        </td>
                        <td>
                          Create log and stored into database.
                          Detail Log can be access in {!! createLink(BeUrl('system-log'), 'Here')  !!}
                          <ul>
                            <li> $activity : text activities </li>
                            <li> $link : link to landing page </li>
                            <li> $userID : <b>_self</b> to open in same tab or <b>_blank</b> to open in new tab  </li>
                          </ul>
                        </td>
                        <td>
                          <sc>setLog($activity, $link='', $userID='')</sc>
                          result : Stored in database system log
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <sc>dateSQL($date=null)</sc>
                        </td>
                        <td>
                          Get current date and formated Y-m-d H:i:s
                          <ul>
                            <li> $date : Default date </li>
                          </ul>
                        </td>
                        <td>
                          <sc>dateSQL()</sc>
                          result : {{ dateSQL() }}
                        </td>
                      </tr> 

                      <tr>
                        <td>
                          <sc>formatNumber($number, $dec=0, $currency=false, $symbol='Rp ', $isPrefix=true)</sc>
                        </td>
                        <td>
                          Format Number
                          <ul>
                            <li> $number : Number </li>
                            <li> $dec : decimal </li>
                            <li> $currency : <b>true</b> to show currency or <b>false</b> to hide currency </li>
                            <li> $symbol : default <b>Rp </b> </li>
                            <li> $isPrefix : <b>true</b> to show symbol in the begin nominal <b>false</b> to show symbol in the after nominal </li>
                          </ul>
                        </td>
                        <td>
                          <sc>formatNumber(1000, $dec=0, true)</sc>
                          result : {{ formatNumber(1000, $dec=0, true) }}
                        </td>
                      </tr> 

                      <tr>
                        <td>
                          <sc>val($row, $key, $default='')</sc>
                        </td>
                        <td>
                          Value array, get value in condition isset.
                          isset(array[key]) ? array[key] : ''
                          <ul>
                            <li> $row : array </li>
                            <li> $key : string, use dot to subkey </li>
                            <li> $default : default to backfill if that value is blank</li>
                          </ul>
                        </td>
                        <td>
                          <sc>
                          $row = {{ echoPre( ['author'=>['name'=>'Yuda Prawira', 'site'=>'http://yuda.tk']] ) }}
                          val($row, 'author.name')
                          </sc>
                          result : {{ val(['author'=>['name'=>'Yuda Prawira', 'site'=>'http://yuda.tk']], 'author.name') }}
                        </td>
                      </tr> 

                      <tr>
                        <td>
                          <sc>getRowArray($row, $primary, $name='*', $subKey='')</sc>
                        </td>
                        <td>
                          Array structured
                          <ul>
                            <li> $row : array / object </li>
                            <li> $primary : uniques field </li>
                            <li> $name : value to display, use asterisk(*) to show all filed</li>
                          </ul>
                        </td>
                        <td>
                          <sc>
                          getRowArray(App\Models\System\User::all(), 'id', 'username')
                          </sc>
                          result : {{ echoPre(getRowArray(App\Models\System\User::all(), 'id', 'username')) }}
                        </td>
                      </tr> 

                      <tr>
                        <td>
                          <sc>text($text, $isEnable=false, $enabledClass='', $disabledClass='')</sc>
                        </td>
                        <td>
                          Array structured
                          <ul>
                            <li> $text : text string </li>
                            <li> $isEnable : <b>true</b> to give class in enabled mode or <b>false</b> to give class in disabled mode </li>
                            <li> $enabledClass : css class </li>
                            <li> $disabledClass : css class </li>
                          </ul>
                        </td>
                        <td>
                          <sc>
                          text($text, $isEnable=false, $enabledClass='', $disabledClass='')
                          </sc>
                          result : {!! text('test text', true, 'text-success', 'text-danger') !!}
                          <br/><br/>
                          <sc>
                          text($text, $isEnable=false, $enabledClass='', $disabledClass='')
                          </sc>
                          result : {!! text('test text', false, 'text-success', 'text-danger') !!}
                        </td>
                      </tr>  


                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div>
        </div>
    </div>
@stop
@push('style')
<style>
  sc {
    background: #e2e0e0;
    display: block;
    padding: 5px;
  }
</style>
@endpush('style')