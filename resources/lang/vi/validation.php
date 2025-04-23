<?php

use Illuminate\Validation\Rules\Password;

return [

    /*
    |--------------------------------------------------------------------------
    | Các dòng thông báo xác thực
    |--------------------------------------------------------------------------
    |
    | Các dòng ngôn ngữ sau chứa các thông báo lỗi mặc định được sử dụng bởi
    | lớp xác thực. Một số quy tắc có nhiều phiên bản như quy tắc kích thước.
    | Hãy thoải mái tùy chỉnh mỗi thông báo này ở đây.
    |
    */

    'accepted'             => ':attribute phải được chấp nhận.',
    'accepted_if'          => ':attribute phải được chấp nhận khi :other là :value.',
    'active_url'           => ':attribute không phải là một URL hợp lệ.',
    'after'                => ':attribute phải là một ngày sau ngày :date.',
    'after_or_equal'       => ':attribute phải là một ngày sau hoặc bằng ngày :date.',
    'alpha'                => ':attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash'           => ':attribute chỉ có thể chứa các chữ cái, số, dấu gạch ngang và gạch dưới.',
    'alpha_num'            => ':attribute chỉ có thể chứa các chữ cái và số.',
    'array'                => ':attribute phải là một mảng.',
    'ascii'                => ':attribute chỉ được chứa ký tự và ký hiệu một byte.',
    'before'               => ':attribute phải là một ngày trước ngày :date.',
    'before_or_equal'      => ':attribute phải là một ngày trước hoặc bằng ngày :date.',
    'between'              => [
        'array'   => ':attribute phải có từ :min đến :max phần tử.',
        'file'    => ':attribute phải có dung lượng từ :min đến :max kilobytes.',
        'numeric' => ':attribute phải nằm trong khoảng :min đến :max.',
        'string'  => ':attribute phải có từ :min đến :max ký tự.',
    ],
    'boolean'              => ':attribute trường phải là true hoặc false.',
    'confirmed'            => ':attribute xác nhận không khớp.',
    'current_password'     => 'Mật khẩu không đúng.',
    'date'                 => ':attribute không phải là một ngày hợp lệ.',
    'date_equals'          => ':attribute phải là một ngày bằng với :date.',
    'date_format'          => ':attribute không đúng định dạng :format.',
    'decimal'              => ':attribute phải có :decimal chữ số thập phân.',
    'declined'             => ':attribute phải bị từ chối.',
    'declined_if'          => ':attribute phải bị từ chối khi :other là :value.',
    'different'            => ':attribute và :other phải khác nhau.',
    'digits'               => ':attribute phải có :digits chữ số.',
    'digits_between'       => ':attribute phải có từ :min đến :max chữ số.',
    'dimensions'           => ':attribute có kích thước hình ảnh không hợp lệ.',
    'distinct'             => ':attribute trường có giá trị trùng lặp.',
    'doesnt_end_with'      => ':attribute không được kết thúc bằng một trong các giá trị sau: :values.',
    'doesnt_start_with'    => ':attribute không được bắt đầu bằng một trong các giá trị sau: :values.',
    'email'                => ':attribute phải là địa chỉ email hợp lệ.',
    'ends_with'            => ':attribute phải kết thúc bằng một trong các giá trị sau: :values.',
    'enum'                 => ':attribute được chọn không hợp lệ.',
    'exists'               => ':attribute được chọn không hợp lệ.',
    'file'                 => ':attribute phải là một tệp tin.',
    'filled'               => ':attribute trường phải có giá trị.',
    'gt'                   => [
        'array'   => ':attribute phải có nhiều hơn :value phần tử.',
        'file'    => ':attribute phải lớn hơn :value kilobytes.',
        'numeric' => ':attribute phải lớn hơn :value.',
        'string'  => ':attribute phải có nhiều hơn :value ký tự.',
    ],
    'gte'                  => [
        'array'   => ':attribute phải có từ :value phần tử trở lên.',
        'file'    => ':attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'numeric' => ':attribute phải lớn hơn hoặc bằng :value.',
        'string'  => ':attribute phải có từ :value ký tự trở lên.',
    ],
    'image'                => ':attribute phải là một hình ảnh.',
    'in'                   => ':attribute được chọn không hợp lệ.',
    'in_array'             => ':attribute không tồn tại trong :other.',
    'integer'              => ':attribute phải là một số nguyên.',
    'ip'                   => ':attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4'                 => ':attribute phải là một địa chỉ IPv4 hợp lệ.',
    'ipv6'                 => ':attribute phải là một địa chỉ IPv6 hợp lệ.',
    'json'                 => ':attribute phải là một chuỗi JSON hợp lệ.',
    'lowercase'            => ':attribute phải là chữ thường.',
    'lt'                   => [
        'array'   => ':attribute phải có ít hơn :value phần tử.',
        'file'    => ':attribute phải nhỏ hơn :value kilobytes.',
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'string'  => ':attribute phải có ít hơn :value ký tự.',
    ],
    'lte'                  => [
        'array'   => ':attribute không được có nhiều hơn :value phần tử.',
        'file'    => ':attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'numeric' => ':attribute phải nhỏ hơn hoặc bằng :value.',
        'string'  => ':attribute phải có ít hơn hoặc bằng :value ký tự.',
    ],
    'mac_address'          => ':attribute phải là một địa chỉ MAC hợp lệ.',
    'max'                  => [
        'array'   => ':attribute không được có nhiều hơn :max phần tử.',
        'file'    => ':attribute không được lớn hơn :max kilobytes.',
        'numeric' => ':attribute không được lớn hơn :max.',
        'string'  => ':attribute không được nhiều hơn :max ký tự.',
    ],
    'mimes'                => ':attribute phải là tệp loại: :values.',
    'mimetypes'            => ':attribute phải là tệp loại: :values.',
    'min'                  => [
        'array'   => ':attribute phải có ít nhất :min phần tử.',
        'file'    => ':attribute phải tối thiểu :min kilobytes.',
        'numeric' => ':attribute phải tối thiểu là :min.',
        'string'  => ':attribute phải có ít nhất :min ký tự.',
    ],
    'multiple_of'          => ':attribute phải là bội số của :value.',
    'not_in'               => ':attribute được chọn không hợp lệ.',
    'not_regex'            => ':attribute có định dạng không hợp lệ.',
    'numeric'              => ':attribute phải là một số.',
    'password'             => [
        'letters'       => ':attribute phải chứa ít nhất một chữ cái.',
        'mixed'         => ':attribute phải chứa ít nhất một chữ hoa và một chữ thường.',
        'numbers'       => ':attribute phải chứa ít nhất một chữ số.',
        'symbols'       => ':attribute phải chứa ít nhất một ký hiệu.',
        'uncompromised' => ':attribute xuất hiện trong dữ liệu rò rỉ. Vui lòng chọn một mật khẩu khác.',
    ],
    'present'              => ':attribute trường phải tồn tại.',
    'prohibited'           => ':attribute bị cấm.',
    'prohibited_if'        => ':attribute bị cấm khi :other là :value.',
    'prohibited_unless'    => ':attribute bị cấm trừ khi :other nằm trong :values.',
    'prohibits'            => ':attribute ngăn không cho :other có mặt.',
    'regex'                => ':attribute có định dạng không hợp lệ.',
    'required'             => ':attribute là bắt buộc.',
    'required_array_keys'  => ':attribute phải chứa các mục cho: :values.',
    'required_if'          => ':attribute là bắt buộc khi :other là :value.',
    'required_unless'      => ':attribute là bắt buộc trừ khi :other nằm trong :values.',
    'required_with'        => ':attribute là bắt buộc khi :values có mặt.',
    'required_with_all'    => ':attribute là bắt buộc khi tất cả :values có mặt.',
    'required_without'     => ':attribute là bắt buộc khi :values không có mặt.',
    'required_without_all' => ':attribute là bắt buộc khi không có :values nào có mặt.',
    'same'                 => ':attribute và :other phải giống nhau.',
    'size'                 => [
        'array'   => ':attribute phải chứa :size phần tử.',
        'file'    => ':attribute phải có dung lượng :size kilobytes.',
        'numeric' => ':attribute phải bằng :size.',
        'string'  => ':attribute phải có :size ký tự.',
    ],
    'starts_with'          => ':attribute phải bắt đầu bằng một trong các giá trị sau: :values.',
    'string'               => ':attribute phải là một chuỗi.',
    'timezone'             => ':attribute phải là một múi giờ hợp lệ.',
    'unique'               => ':attribute đã tồn tại.',
    'uploaded'             => ':attribute tải lên thất bại.',
    'uppercase'            => ':attribute phải là chữ in hoa.',
    'url'                  => ':attribute không phải là một URL hợp lệ.',
    'uuid'                 => ':attribute phải là một UUID hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Tùy chỉnh thông báo xác thực
    |--------------------------------------------------------------------------
    |
    | Ở đây bạn có thể chỉ định thông báo xác thực tùy chỉnh cho các thuộc tính bằng cách sử dụng
    | quy ước "attribute.rule" để đặt dòng tùy chỉnh cụ thể.
    |
    */

    'custom' => [
        // 'email.required' => 'Bạn chưa nhập email.',
      'email.failed' => 'Email không tồn tại trong hệ thống.',
      'password.failed' => 'Mật khẩu không chính xác.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tùy chỉnh thuộc tính
    |--------------------------------------------------------------------------
    |
    | Các dòng sau được sử dụng để thay thế tên thuộc tính bằng từ thân thiện hơn
    | với người dùng như "E-Mail" thay vì "email".
    |
    */

    'attributes' => [
        'name' => 'họ và tên',
        'email' => 'email',
        'password' => 'mật khẩu',
        'password_confirmation' => 'xác nhận mật khẩu',
    ],

];
