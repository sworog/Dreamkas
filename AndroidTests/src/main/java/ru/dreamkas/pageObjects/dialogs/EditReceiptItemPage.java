package ru.dreamkas.pageObjects.dialogs;

import net.thucydides.core.annotations.findby.By;
import net.thucydides.core.annotations.findby.FindBy;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.Button;
import ru.dreamkas.pageObjects.elements.Input;
import ru.dreamkas.pageObjects.elements.TextView;

public class EditReceiptItemPage extends CommonPageObject {

    public EditReceiptItemPage(WebDriver driver) {
        super(driver);

        putElementable("Итог", new TextView(this, "lblTotal"));
        putElementable("Наименование", new TextView(this, "lblProductName"));

        putElementable("Цена продажи", new Input(this, "txtSellingPrice"));
        putElementable("Количество", new Input(this, "txtValue"));

        putElementable("Сохранить", new Button(this, "btnSave"));
        putElementable("+", new Button(this, "btnUp"));
        putElementable("-", new Button(this, "btnDown"));
        putElementable("Закрыть", new Button(this, "btnCloseModal"));
        putElementable("Удалить из чека", new Button(this, "btnRemoveFromReceipt"));
    }
}
