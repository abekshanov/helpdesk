<?php

namespace App\Exceptions;

use Exception;

class LogicException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        return redirect()->route('orders.index')->withErrors($this->getMessage());
    }
}
