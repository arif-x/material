<nav class="navbar">
	<a href="#" class="sidebar-toggler">
		<i data-feather="menu"></i>
	</a>
	<div class="navbar-content" style="background-color: #727cf5">
		<ul class="navbar-nav">
			<li class="nav-item text-white">{{auth()->user()->name}}</li>
			<li class="nav-item dropdown nav-profile">
				<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img src="{{asset('/assets/images/image-header.png')}}" alt="profile">
				</a>
				<div class="dropdown-menu" aria-labelledby="profileDropdown">
					<div class="dropdown-header d-flex flex-column align-items-center">
						<div class="figure mb-3">
							<img src="{{asset('/assets/images/image-header.png')}}" alt="">
						</div>
						<div class="info text-center">
							<p class="name font-weight-bold mb-0">{{auth()->user()->name}}</p>
							<p class="email text-muted mb-3">{{auth()->user()->email}}</p>
						</div>
					</div>
					<div class="dropdown-body">
						<ul class="profile-nav p-0 pt-3">
							<!-- <li class="nav-item">
								<a href="pages/general/profile.html" class="nav-link">
									<i data-feather="user"></i>
									<span>Profile</span>
								</a>
							</li>
							<li class="nav-item">
								<a href="javascript:;" class="nav-link">
									<i data-feather="edit"></i>
									<span>Edit Profile</span>
								</a>
							</li>
							<li class="nav-item">
								<a href="javascript:;" class="nav-link">
									<i data-feather="repeat"></i>
									<span>Switch User</span>
								</a>
							</li> -->
							<li class="nav-item">
								<a href="javascript:;" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
									<i data-feather="log-out"></i>
									<span>Log Out</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</li>
		</ul>
	</div>
</nav>