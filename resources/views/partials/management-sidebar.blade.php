<div class="sidebar-content"><nav class="nav flex-column">
  <a class="nav-link active" aria-current="page" href="{{ route('management.dashboard') }}">
    <div id="dashboard-nav">
      <svg xmlns="http://www.w3.org/2000/svg" 
          width="1rem" height="1rem" 
          viewBox="0 0 24 24" fill="none" 
          class="icon">
          
          <rect width="24" height="24" fill="transparent"/>
          <path d="M15.024 22C16.2771 22 17.3524 21.9342 18.2508 21.7345C19.1607 21.5323 19.9494 21.1798 20.5646 20.5646C21.1798 19.9494 21.5323 19.1607 21.7345 18.2508C21.9342 17.3524 22 16.2771 22 15.024V12C22 10.8954 21.1046 10 20 10H12C10.8954 10 10 10.8954 10 12V20C10 21.1046 10.8954 22 12 22H15.024Z" fill="currentColor"/>
          <path d="M2 15.024C2 16.2771 2.06584 17.3524 2.26552 18.2508C2.46772 19.1607 2.82021 19.9494 3.43543 20.5646C4.05065 21.1798 4.83933 21.5323 5.74915 21.7345C5.83628 21.7538 5.92385 21.772 6.01178 21.789C7.09629 21.9985 8 21.0806 8 19.976L8 12C8 10.8954 7.10457 10 6 10H4C2.89543 10 2 10.8954 2 12V15.024Z" fill="currentColor"/>
          <path d="M8.97597 2C7.72284 2 6.64759 2.06584 5.74912 2.26552C4.8393 2.46772 4.05062 2.82021 3.4354 3.43543C2.82018 4.05065 2.46769 4.83933 2.26549 5.74915C2.24889 5.82386 2.23327 5.89881 2.2186 5.97398C2.00422 7.07267 2.9389 8 4.0583 8H19.976C21.0806 8 21.9985 7.09629 21.789 6.01178C21.772 5.92385 21.7538 5.83628 21.7345 5.74915C21.5322 4.83933 21.1798 4.05065 20.5645 3.43543C19.9493 2.82021 19.1606 2.46772 18.2508 2.26552C17.3523 2.06584 16.2771 2 15.024 2H8.97597Z" fill="currentColor"/>
      </svg>
      <p>Dashboard</p>
    </div>
  </a>
  <div id="rooms-nav" onclick="hide_show_room_part()">
    <svg fill="currentColor" width="1rem" height="1rem" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M5 10C3.347656 10 2 11.347656 2 13L2 26.8125C3.296875 25.6875 4.9375 24.777344 7 24.0625L7 20C7 17.339844 11.542969 17 15.5 17C19.457031 17 24 17.339844 24 20L24 22C24.335938 21.996094 24.65625 22 25 22C25.34375 22 25.664063 21.996094 26 22L26 20C26 17.339844 30.542969 17 34.5 17C38.457031 17 43 17.339844 43 20L43 24.03125C45.058594 24.742188 46.691406 25.671875 48 26.8125L48 13C48 11.347656 46.652344 10 45 10 Z M 25 24C5.90625 24 -0.015625 27.53125 0 37L50 37C50.015625 27.46875 44.09375 24 25 24 Z M 0 39L0 50L7 50L7 46C7 44.5625 7.5625 44 9 44L41 44C42.4375 44 43 44.5625 43 46L43 50L50 50L50 39Z"/></svg>
    <p>Rooms</p>
    <svg width="1rem" height="1rem" viewBox="0 0 1024 1024" id="room-arrow" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M903.232 256l56.768 50.432L512 768 64 306.432 120.768 256 512 659.072z" fill="currentColor" /></svg>
  </div>
  <div class="room_button_holder">
      <a class="nav-link active" aria-current="page" href="{{ route('management.manage-rooms') }}">
          <div id="room-av">
              Manage Rooms
          </div>
      </a>
      <a class="nav-link active" aria-current="page" href="{{ route('management.room-types') }}">
          <div id="room-type">
            Room Types
          </div>
      </a>
  </div>
  <div class="buttons-part2">
    <div id="deals-nav" onclick="show_hide_deals()">
    <svg fill="#697A8D" width="1rem" height="1rem"viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2ZM8.5,6.5a2,2,0,1,1-2,2A2,2,0,0,1,8.5,6.5Zm.207,10.207a1,1,0,1,1-1.414-1.414l8-8a1,1,0,1,1,1.414,1.414ZM15.5,17.5a2,2,0,1,1,2-2A2,2,0,0,1,15.5,17.5Z"></path></g></svg>
      Deals
      <svg width="1rem" height="1rem" viewBox="0 0 1024 1024" class="icon" id="deal-arrow" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M903.232 256l56.768 50.432L512 768 64 306.432 120.768 256 512 659.072z" fill="#697A8D" /></svg>
    </div>
    <a class="nav-link active" aria-current="page" href="{{ route('management.promo') }}">
      <div id="promos-nav">
        Promotions & Offers
      </div>
    </a>
    <a class="nav-link active" aria-current="page" href="{{ route('management.vouchers') }}">
      <div id="voucher-nav">
        Vouchers
      </div>
    </a>
    <a class="nav-link active" aria-current="page" href="{{ route('management.points') }}">
      <div id="points-nav">
        Loyalty Program
      </div>
    </a>
    <div class="buttons-part3">
        <div id="user-nav" onclick="show_hide_users()">
              <svg width="1rem" height="1rem" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="icon">
                  <g id="style=fill">
                  <g id="profile">
                  <path id="vector (Stroke)" fill-rule="evenodd" clip-rule="evenodd" d="M6.75 6.5C6.75 3.6005 9.1005 1.25 12 1.25C14.8995 1.25 17.25 3.6005 17.25 6.5C17.25 9.3995 14.8995 11.75 12 11.75C9.1005 11.75 6.75 9.3995 6.75 6.5Z" fill="inherit"/>
                  <path id="rec (Stroke)" fill-rule="evenodd" clip-rule="evenodd" d="M4.25 18.5714C4.25 15.6325 6.63249 13.25 9.57143 13.25H14.4286C17.3675 13.25 19.75 15.6325 19.75 18.5714C19.75 20.8792 17.8792 22.75 15.5714 22.75H8.42857C6.12081 22.75 4.25 20.8792 4.25 18.5714Z" fill="inherit"/>
                  </g>
                  </g>
              </svg>
              Users
              <svg width="1rem" height="1rem" viewBox="0 0 1024 1024" class="icon" id="user-arrow" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M903.232 256l56.768 50.432L512 768 64 306.432 120.768 256 512 659.072z" fill="#697A8D" /></svg>
        </div>
        <a class="nav-link active" aria-current="page" href="{{ route('management.guest') }}">
              <div id="man-guest-nav">
                  Guest
              </div>
            </a>
            <a class="nav-link active" aria-current="page" href="{{ route('management.employee') }}">
              <div id="employee-nav">
                  Employee
              </div>
            </a>
        <div class="buttons-part4">
            <a class="nav-link active" aria-current="page" href="{{ route('management.performance') }}">
              <div id="perf-nav">
                  <svg version="1.1" width="1rem"  height="1rem" id="_x32_" class="icon" xmlns="http://www.w3.org/2000/svg" 
                  xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="currentColor">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                  </g><g id="SVGRepo_iconCarrier"> <g> <rect x="56" y="317.484" class="st0" width="80" height="146.516"></rect> <rect x="176" y="237.484" class="st0" width="80" height="226.516"></rect> <rect x="296" y="141.484" class="st0" width="80" height="322.516"></rect> <rect x="416" y="45.484" class="st0" width="80" height="418.516"></rect> <polygon class="st0" points="16,496 16,0 0,0 0,496 0,512 16,512 512,512 512,496 "></polygon> </g> </g></svg>
                  Performance
              </div>
            </a>
            <a class="nav-link active" aria-current="page" href="{{ route('management.history') }}">
              <div id="history-nav">
                  <svg viewBox="0 0 16 16" class="icon" width="1rem" height="1rem" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M1.5 1.25a.75.75 0 011.5 0v1.851A7 7 0 111 8a.75.75 0 011.5 0 5.5 5.5 0 101.725-4H5.75a.75.75 0 010 1.5h-3.5a.75.75 0 01-.75-.75v-3.5z"></path> <path d="M8.25 4a.75.75 0 01.75.75v3.763l1.805.802a.75.75 0 01-.61 1.37l-2.25-1A.75.75 0 017.5 9V4.75A.75.75 0 018.25 4z"></path> </g> </g></svg>
                  History
              </div>
            </a>
            <a class="nav-link active" aria-current="page" href="{{ route('management.faq') }}">
              <div id="faq-nav">
              <svg fill="currentColor" class="icon" width="1rem" height="1rem" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,22A10,10,0,1,0,2,12,10,10,0,0,0,12,22Zm0-2a1.5,1.5,0,1,1,1.5-1.5A1.5,1.5,0,0,1,12,20ZM8,8.994a3.907,3.907,0,0,1,2.319-3.645,4.061,4.061,0,0,1,3.889.316,4,4,0,0,1,.294,6.456,3.972,3.972,0,0,0-1.411,2.114,1,1,0,0,1-1.944-.47,5.908,5.908,0,0,1,2.1-3.2,2,2,0,0,0-.146-3.23,2.06,2.06,0,0,0-2.006-.14,1.937,1.937,0,0,0-1.1,1.8A1,1,0,0,1,8,9Z"/></svg>
                  FAQs
              </div>
            </a>
        </div>
      </div>
  </div>
</nav>
</div>
