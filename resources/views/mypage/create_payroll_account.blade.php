@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    口座登録

    <script>window.onload = viewChange;</script>
    <form id="form" action="" method="post">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <div class="form-group">

            <label for="loginid">{{ __('金融機関名') }}</label>
            <select name="bank_select" id="bank" class="form-control" onchange="viewChange();">
                <option value="none" {{$account['bank']=='' ? '' : 'selected'}}>選択してください</option>
                <option value="yuutyo" {{$account['bank']=='yuutyo' ? 'selected' : ''}}>ゆうちょ</option>
                <option value="mizuho" {{$account['bank']=='mizuho' ? 'selected' : ''}}>みずほ銀行</option>
                <option value="mitsubishi" {{$account['bank']=='mitsubishi' ? 'selected' : ''}}>三菱UFJ銀行</option>
                <option value="mitsui" {{$account['bank']=='mitsui' ? 'selected' : ''}}>三井住友銀行</option>
                <option value="risona" {{$account['bank']=='risona' ? 'selected' : ''}}>りそな銀行</option>
                <option value="other" {{(($account['bank'] != '') && $account['bank'] != 'yuutyo') && ($account['bank'] != 'mizuho') && ($account['bank'] != 'mitsubishi') && ($account['bank'] != 'mitsui') && ($account['bank'] != 'risona') ? 'selected' : ''}}>その他の金融機関</option>
            </select>

            <div id="parent_box">
                <!-- if その他の場合 -->
                <div id="other_box">
                    <label for="bank_code">{{ __('金融機関番号（4桁）') }}</label>
                    <input id="bank_code" type="text" class="form-control" value="{{$account['bank_code']}}" name="bank_code">

                    <label for="bank">{{ __('金融機関名') }}</label>
                    <input id="bank" type="text" class="form-control" value="{{$account['bank']}}" name="bank">
                </div>
                <!-- endif -->

                <!-- if ゆうちょ以外の場合 -->
                <div id="else_yuutyo_box">
                    <label for="branch_code">{{ __('店舗コード（3桁）') }}</label>
                    <input id="branch_code" type="text" class="form-control" value="{{$account['branch_code']}}" name="branch_code">
                </div>
                <!-- endif -->

                <div>
                    <label for="branch">{{ __('店舗名') }}</label>
                    <input id="branch" type="text" class="form-control" value="{{$account['branch']}}" name="branch" required>

                    <label for="account_type">{{ __('預金種目') }}</label>
                    <input id="account_type1" type="radio" name="account_type" value="savings" {{$account['account_type']=='savings' ? 'checked' : ''}}>普通（総合）
                    <input id="account_type2" type="radio" name="account_type" value="current" {{$account['account_type']=='current' ? 'checked' : ''}}>当座
                    <br>

                    <label for="symbol_number">{{ __('口座番号（7桁）') }}</label>
                    <input id="symbol_number" type="text" class="form-control" value="{{$account['symbol_number']}}" name="symbol_number">

                    <label for="name">{{ __('預金者名（カタカナ）') }}</label>
                    <input id="name" type="text" class="form-control" value="{{$account['bank']}}" name="name">
                </div>
            </div>
        </div>
        @if($account['bank'])
        <button type="submit" name="submit" class="btn btn-primary">{{ __('更新') }}</button>
        @else
        <button type="submit" name="submit" class="btn btn-primary">{{ __('登録') }}</button>
        @endif
    </form>
@endsection
  
@include('layout.footer')

<script>
function viewChange(){
    if(document.getElementById('bank')){
        id = document.getElementById('bank').value;
        if(id == 'none'){
            document.getElementById('parent_box').style.display = "none";
            document.getElementById('other_box').style.display = "none";
            document.getElementById('else_yuutyo_box').style.display = "none";
            document.getElementById('form').action = "";
        }else if(id == 'yuutyo'){
            document.getElementById('parent_box').style.display = "";
            document.getElementById('other_box').style.display = "none";
            document.getElementById('else_yuutyo_box').style.display = "none";
            document.getElementById('form').action = "save_yuutyo_payroll_account";
        }
        else if(id == 'other'){
            document.getElementById('parent_box').style.display = "";
            document.getElementById('other_box').style.display = "";
            document.getElementById('else_yuutyo_box').style.display = "";
            document.getElementById('form').action = "save_payroll_account";
        }
        else{
            document.getElementById('parent_box').style.display = "";
            document.getElementById('other_box').style.display = "none";
            document.getElementById('else_yuutyo_box').style.display = "";
            document.getElementById('form').action = "save_major_payroll_account";
        }
    }
}

</script>