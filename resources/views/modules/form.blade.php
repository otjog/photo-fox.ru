<div class="container">
    <form method="POST" role="form" class="needs-validation" novalidate>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="name">Имя</label>
                <input name='name' type="text" class="form-control" placeholder="Имя" required>
                <div class="invalid-feedback">
                    Пожалуйста, введите Имя
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Email" required>
                <div class="invalid-feedback">
                    Пожалуйста, введите Email
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="phone">Телефон</label>
                <input name="phone" type="number" class="form-control" placeholder="Телефон" required>
                <div class="invalid-feedback">
                    Пожалуйста, введите номер телефона
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="message">Ваше сообщение</label>
            <textarea name="message" class="form-control" rows="3" required></textarea>
            <div class="invalid-feedback">
                Пожалуйста, введите сообщение
            </div>
        </div>
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input name="check" class="form-check-input" type="checkbox" required>Я согласен на обработку персональных данных
                    <div class="invalid-feedback">
                        Для отправки сообщения необходимо согласиться с условиями обработки персональных данных
                    </div>
                </label>
            </div>
        </div>
        <input type="hidden" name="type" value="question">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-primary" >Отправить</button>
    </form>
</div>