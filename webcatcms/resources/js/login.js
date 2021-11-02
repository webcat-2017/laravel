import('bootstrap/dist/js/bootstrap.bundle');
import('jquery-validation/dist/jquery.validate');
import('jquery-validation/dist/additional-methods');
import('toastr/toastr');
import('./adminlte/adminlte');
import jquery from "jquery";
import validation from 'jquery-validation';
import toastr from 'toastr';
export default (window.$ = window.jQuery = jquery,window.validation = validation,window.toastr = toastr);

