<?php

namespace Tests\Feature;

use Tests\TestCase;

class CancelOperationRouteTest extends TestCase
{
    public function test_operation_views_use_named_cancel_route()
    {
        $views = [
            resource_path('views/operator/operation_list.blade.php'),
            resource_path('views/operator/all_operation_list.blade.php'),
            resource_path('views/operator/ope_dashboard.blade.php'),
        ];

        foreach ($views as $view) {
            $this->assertFileExists($view);
            $content = file_get_contents($view);
            $this->assertStringContainsString("route('cancel_operation'", $content);
            $this->assertStringNotContainsString('href="/cancel_operation/', $content);
        }
    }
}
