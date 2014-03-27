package project.lighthouse.autotests.pages.commercialManager.store;


import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

@DefaultUrl("/stores/create")
public class StoreCreatePage extends CommonPageObject {

    public StoreCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("number", new Input(this, "number"));
        put("address", new Input(this, "address"));
        put("contacts", new Input(this, "contacts"));
    }

    public void createButtonClick() {
        new ButtonFacade(this, "Добавить").click();
        new PreLoader(getDriver()).await();
    }

    public void saveButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
        new PreLoader(getDriver()).await();
    }
}
