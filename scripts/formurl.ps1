# Подключаем сборки
Add-Type -AssemblyName System.Windows.Forms
Add-Type -AssemblyName System.Drawing

# Создаём форму
$form = New-Object System.Windows.Forms.Form
$form.Text = "URL"
$form.Width = 500
$form.Height = 125

# Создаём текстбокс
$tb = New-Object System.Windows.Forms.TextBox
$tb.Left = 10
$tb.Top = 10
$tb.Width = 450
$form.Controls.Add($tb)

# Создаём кнопку
$btn = New-Object System.Windows.Forms.Button
$btn.Text = "OK"
$btn.Left = 250-$btn.Width
$btn.Top = 40
$btn.Add_Click({
    $form.Tag = $tb.Text
    $form.Close()
})
$form.Controls.Add($btn)

# Показываем форму
$form.ShowDialog() | Out-Null

# Возвращаем результат
Write-Output $form.Tag
