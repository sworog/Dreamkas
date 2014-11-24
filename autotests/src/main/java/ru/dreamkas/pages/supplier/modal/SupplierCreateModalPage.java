package ru.dreamkas.pages.supplier.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class SupplierCreateModalPage extends ModalWindowPage {

    public SupplierCreateModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@id, 'modal_supplier') and contains(@class, 'modal_visible')]";
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    @Override
    public void createElements() {
        put("name", new Input(this, "//*[@name='name']"));
        put("address", new Input(this, "//*[@name='address']"));
        put("phone", new Input(this, "//*[@name='phone']"));
        put("email", new Input(this, "//*[@name='email']"));
        put("contactPerson", new Input(this, "//*[@name='contactPerson']"));
        put("заголовок успешного удаления поставщика", new NonType(this, "//*[@name='successRemoveTitle']"));
        put("название удаленного поставщика", new NonType(this, "//*[@name='removedSupplierName']"));
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }

    @Override
    public void deleteButtonClick() {
        clickInTheModalWindowByXpath("//*[@class='removeLink']");
    }

    @Override
    public void confirmDeleteButtonClick() {
        clickInTheModalWindowByXpath("//*[@class='confirmLink__confirmation']//*[@class='removeLink form_supplier__removeLink']");
    }
}
