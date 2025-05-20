{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />
<x-backpack::menu-item title="Forum categories" icon="la la-question" :link="backpack_url('forum-categories')" />
<x-backpack::menu-item title="Forumthreads" icon="la la-question" :link="backpack_url('forumthreads')" />
<x-backpack::menu-item title="Forum posts" icon="la la-question" :link="backpack_url('forum-post')" />
<x-backpack::menu-item title="Forum post reports" icon="la la-question" :link="backpack_url('forum-post-report')" />