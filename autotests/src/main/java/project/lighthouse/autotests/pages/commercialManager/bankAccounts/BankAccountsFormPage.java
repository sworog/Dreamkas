package project.lighthouse.autotests.pages.commercialManager.bankAccounts;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.Textarea;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

public class BankAccountsFormPage extends CommonPageObject {
    public BankAccountsFormPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("bic", new Input(this, "bic"));
        put("bankName", new Input(this, "bankName"));
        put("bankAddress", new Textarea(this, "bankAddress"));
        put("correspondentAccount", new Input(this, "correspondentAccount"));
        put("account", new Input(this, "account"));
    }

    public void createFormButtonClick() {
        new ButtonFacade(this, "Добавить").click();
        new PreLoader(getDriver()).await();
    }

    public void saveFormButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
        new PreLoader(getDriver()).await();
    }
}
