<?php

test('root redirects to swagger documentation', function () {
    $response = $this->get('/');

    $response->assertRedirect('/docs');
});
