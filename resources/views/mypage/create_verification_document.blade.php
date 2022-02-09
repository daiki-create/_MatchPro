@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    本人確認登録

    @if($identified_status == 'checking')
    <p>本人確認中です。変更する場合は以下のフォームより更新してください。</p>

    @elseif($identified_status == 'identified')
    <p>本人確認が完了しています。</p>
    @endif

    @if($identified_status != 'identified')
    <form action="{{ url('mypage/save_verification_document') }}" method="post" enctype="multipart/form-data">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <div class="form-group">
            <label for="bank_code">{{ __('運転免許証') }}</label>
            <input id="document_type1" type="radio" name="document_type" value="drivers_license" onchange="viewChange()" checked>

            <label for="bank_code">{{ __('保険証') }}</label>
            <input id="document_type2" type="radio" name="document_type" value="health_insurance_card" onchange="viewChange()">

            <label for="bank_code">{{ __('パスポート') }}</label>
            <input id="document_type3" type="radio" name="document_type" value="passport" onchange="viewChange()">

            <label for="img_front">{{ __('オモテ') }}</label>
            <input id="img_front" type="file" class="form-control" name="img_front" accept="image/*" required>

            <div id="back">
                <label for="img_back">{{ __('ウラ') }}</label>
                <input id="img_back" type="file" class="form-control" name="img_back" accept="image/*">
            </div>
        </div>
        @if(isset($document))
        <button type="submit" name="submit" class="btn btn-primary">{{ __('更新') }}</button>
        @else
        <button type="submit" name="submit" class="btn btn-primary">{{ __('登録') }}</button>
        @endif
    </form>
    @endif
@endsection
  
@include('layout.footer')

<script>
function viewChange() {
    var document_types = document.getElementsByName("document_type");
    if(document_types[0].checked || document_types[1].checked)
    {
        document.getElementById('back').style.display = "";
    }
    if(document_types[2].checked)
    {
        document.getElementById('back').style.display = "none";
    }
}
viewChange();
</script>