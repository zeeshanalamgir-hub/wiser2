<?php

Route::get('/', 'TicketController@supportTicket')->name('ticket');
Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');

?>
