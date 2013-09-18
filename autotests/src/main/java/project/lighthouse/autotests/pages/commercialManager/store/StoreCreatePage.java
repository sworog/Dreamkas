package project.lighthouse.autotests.pages.commercialManager.store;


import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;

@DefaultUrl("/stores/create")
public class StoreCreatePage extends CommonPageObject {

    public StoreCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("number", new Input(this, "number"));
        items.put("address", new Input(this, "address"));
        items.put("contacts", new Input(this, "contacts"));
    }

    public void createButtonClick() {
        new ButtonFacade(getDriver(), "Добавить").click();
        //TODO common preloader object waiter
    }

    public void saveButtonClick() {
        new ButtonFacade(getDriver(), "Сохранить").click();
        //TODO common preloader object waiter
    }
}
