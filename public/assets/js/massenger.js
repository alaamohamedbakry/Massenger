$(document).ready(function () {
    $('.chat-form').on('submit', function (e) {
        e.preventDefault();

        let msg = $(this).find('textarea').val().trim();
        let token = localStorage.getItem('token'); // استرجاع التوكن من LocalStorage

        console.log("Token Retrieved:", token); // طباعة التوكن في Console للتحقق منه

        if (!token) {
            alert("⚠️ لا يوجد توكن محفوظ! تأكد من تسجيل الدخول.");
            return;
        }

        if (!msg) {
            alert("⚠️ الرجاء إدخال رسالة!");
            return;
        }

        $.ajax({
            url: "http://127.0.0.1:8000/api/messages",
            type: "POST",
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                user_id: 1,
                massege: msg, // ✅ تصحيح اسم الحقل ليطابق المطلوب في API
                conversation_id: 7
            }),
            success: function (response) {
                console.log("✅ تم إرسال الرسالة:", response);

                // إضافة الرسالة إلى المحادثة بعد نجاح الإرسال
                $('#chat-body').append(`
                    <div class="message message-out">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-profile" class="avatar avatar-responsive">
                            <img class="avatar-img" src="" alt="">
                        </a>
                        <div class="message-inner">
                            <div class="message-body">
                                <div class="message-content">
                                    <div class="message-text">
                                        <p>${msg}</p>
                                    </div>
                                    <div class="message-action">
                                        <div class="dropdown">
                                            <a class="icon text-muted" href="#" role="button" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="12" cy="5" r="1"></circle>
                                                    <circle cx="12" cy="19" r="1"></circle>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="message-footer">
                                <span class="extra-small text-muted">Just Now</span>
                            </div>
                        </div>
                    </div>
                `);

                // مسح حقل إدخال الرسالة بعد الإرسال
                $('.chat-form').find('textarea').val('');
            },
            error: function (xhr) {
                console.log("❌ خطأ أثناء إرسال الرسالة:", xhr.responseText);

                if (xhr.status === 401) {
                    alert("⚠️ غير مصرح لك بإرسال الرسائل! تأكد من تسجيل الدخول أو تحديث التوكن.");
                } else {
                    alert("⚠️ حدث خطأ أثناء الإرسال، يرجى المحاولة لاحقًا.");
                }
            }
        });
    });
});
