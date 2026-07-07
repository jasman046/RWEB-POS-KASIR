@extends('layouts.app')

@section('title', 'Transactions')

@section('page-title', 'PRODUCT')

@section('content')

<div class="transaction-dashboard">

    <div class="transaction-header">

        <div class="transaction-header-left">

            <h2>Debit Card</h2>

            <button class="add-card-btn">
                + Add Card
            </button>

        </div>

        <div class="transaction-header-right">

            <h2>My Expense</h2>

        </div>

    </div>

    <div class="transaction-top">

        <div class="transaction-card-section">

            @include('transaction.components.cards')

        </div>

        <div class="transaction-chart-section">

            @include('transaction.components.chart')

        </div>

    </div>

    <div class="transaction-bottom">

        @include('transaction.components.table')

    </div>

</div>

@endsection