  <div class="wrap-left">
      <div class="sort">
          <div class="sort-bb"></div>
          <div class="avatar-box">
              <img src="{{Attachments::getAvatar(Session::get('uid'))}}?t={{time()}}" width="140" height="140" />
          </div>
          
          @if(Auth::user()->status == 2)
          <div class="sort-item" style="padding-left:60px;">
            <span style="background-color: #1db5a9;padding:5px 10px;">
              <a href="/profile/verify" style="color:#fff;">未验证</a>
            </span>
          </div>
          @endif

         <!--  <div class="sort-bb"></div>
          <div class="sort-item @if($_SERVER["REQUEST_URI"] == '/column/static') sort-item-sel sort-nkm-sel @else sort-nkm @endif"><a href="/column/static">科目选择</a><div class="sort-nsj"></div></div>
          -->
          <div class="sort-bb"></div>
          <div class="sort-item @if($_SERVER["REQUEST_URI"] == '/profile') sort-item-sel sort-ngr-sel @else sort-ngr @endif"><a href="/profile">个人资料</a><div class="sort-nsj"></div></div>
          <div class="sort-bb"></div>
          <div class="sort-item @if($_SERVER["REQUEST_URI"] == '/profile/passwd') sort-item-sel sort-nnpw-sel @else sort-nnpw @endif"><a href="/profile/passwd">密码管理</a><div class="sort-nsj"></div></div>
          <div class="sort-bb"></div>
          @if(Session::get('utype') == 0)
          <div class="sort-item @if($_SERVER["REQUEST_URI"] == '/profile/up') sort-item-sel sort-npw-sel @else sort-npw @endif"><a href="/profile/up">升级教师</a><div class="sort-nsj"></div></div>
          <div class="sort-bb"></div>
          @endif
      </div>
  </div>