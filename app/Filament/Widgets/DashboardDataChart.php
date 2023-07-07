<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Url;


class DashboardDataChart extends LineChartWidget
{
    protected static ?string $heading = 'Mikit Rooms Monthly Statistical Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Mikit Rooms Created Per Month',
                    'data' => [
                        Url::whereMonth('created_at', '1')->count(),
                        Url::whereMonth('created_at', '2')->count(),
                        Url::whereMonth('created_at', '3')->count(),
                        Url::whereMonth('created_at', '4')->count(),
                        Url::whereMonth('created_at', '5')->count(),
                        Url::whereMonth('created_at', '6')->count(),
                        Url::whereMonth('created_at', '7')->count(),
                        Url::whereMonth('created_at', '8')->count(),
                        Url::whereMonth('created_at', '9')->count(),
                        Url::whereMonth('created_at', '10')->count(),
                        Url::whereMonth('created_at', '11')->count(),
                        Url::whereMonth('created_at', '12')->count(),
                    ],
                    'borderColor' => 'blue',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }
}
