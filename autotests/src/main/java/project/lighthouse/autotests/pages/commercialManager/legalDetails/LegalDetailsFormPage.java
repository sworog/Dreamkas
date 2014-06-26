package project.lighthouse.autotests.pages.commercialManager.legalDetails;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByLabel;
import project.lighthouse.autotests.elements.items.Textarea;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

public class LegalDetailsFormPage extends CommonPageObject {
    public LegalDetailsFormPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("type", new SelectByLabel(this, By.name("type"), By.className("inputRadio__text")));
        put("fullName", new Input(this, "fullName"));
        put("legalAddress", new Textarea(this, "legalAddress"));
        put("inn", new Input(this, "inn"));
        put("okpo", new Input(this, "okpo"));
        put("ogrnip", new Input(this, "ogrnip"));
        put("certificateNumber", new Input(this, "certificateNumber"));
        put("certificateDate", new Input(this, "certificateDate"));
    }

    public void saveButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
        new PreLoader(getDriver()).await();
    }
}
