package project.lighthouse.autotests.pages.supplier.modal;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class SupplierCreateModalPage extends ModalWindowPage {

    public SupplierCreateModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal-supplierAdd']";
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
    }
}
