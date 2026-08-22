<?php

it('serves the Scribe documentation', function () {
    $response = $this->get('/docs');

    $response->assertOk();
});
