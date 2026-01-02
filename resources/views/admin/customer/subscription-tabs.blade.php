@extends('layouts.app')
@section('title', 'Customer Subscriptions')

@section('content')
@php
    // ✅ Read tab from controller or URL (?tab=renewal)
    $tab = $activeTab ?? request('tab', 'subscribed');
    $allowed = ['subscribed','renewal','unsubscribed'];
    if(!in_array($tab, $allowed)) $tab = 'subscribed';
@endphp

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            @include('common.alert')

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $tab=='subscribed' ? 'active' : '' }}"
                       data-bs-toggle="tab" href="#subscribed" role="tab">
                        Subscribed
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab=='renewal' ? 'active' : '' }}"
                       data-bs-toggle="tab" href="#renewal" role="tab">
                        Renewal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab=='unsubscribed' ? 'active' : '' }}"
                       data-bs-toggle="tab" href="#unsubscribed" role="tab">
                        Unsubscribed
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade {{ $tab=='subscribed' ? 'show active' : '' }}" id="subscribed" role="tabpanel">
                    @include('admin.customer.subscription-list', ['customers' => $subscribed, 'type' => 'Subscribed'])
                </div>

                <div class="tab-pane fade {{ $tab=='renewal' ? 'show active' : '' }}" id="renewal" role="tabpanel">
                    @include('admin.customer.subscription-list', ['customers' => $renewal, 'type' => 'Renewal Due'])
                </div>

                <div class="tab-pane fade {{ $tab=='unsubscribed' ? 'show active' : '' }}" id="unsubscribed" role="tabpanel">
                    @include('admin.customer.subscription-list', ['customers' => $unsubscribed, 'type' => 'Unsubscribed'])
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
