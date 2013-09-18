package project.lighthouse.autotests.pages.administrator;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;

@DefaultUrl("/settings")
public class SettingsPage extends CommonPageObject {

    public SettingsPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("set10-integration-url", new Input(this, "set10-integration-url"));
        items.put("set10-integration-login", new Input(this, "set10-integration-login"));
        items.put("set10-integration-password", new Input(this, "set10-integration-password"));
    }

    public void saveSettingButtonClick() {
        new ButtonFacade(getDriver(), "Сохранить").click();
        //TODO common preloader object waiter
    }
}
