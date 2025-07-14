<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Note;

class NotesChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';


    protected function getType(): string
    {
        return 'line';
    }
    protected function getData(): array
    {
        $note = Note::selectRaw('MONTH(created_at) as month, SUM(id) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Notes',
                    'data' => $note->values(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                ],
            ],
            'labels' => $note->keys()->map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)))->toArray(),
        ];
    }
}
