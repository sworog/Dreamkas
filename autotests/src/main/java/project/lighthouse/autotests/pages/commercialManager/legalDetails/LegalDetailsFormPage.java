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
        put("legalDetails.fullName", new Input(this, "legalDetails.fullName"));
        put("legalDetails.legalAddress", new Textarea(this, By.cssSelector("fieldset[rel='entrepreneur'] [name='legalDetails.legalAddress']")));
        put("legalDetails.inn", new Input(this, By.cssSelector("fieldset[rel='entrepreneur'] [name='legalDetails.inn']")));
        put("legalDetails.okpo", new Input(this, By.cssSelector("fieldset[rel='entrepreneur'] [name='legalDetails.okpo']")));
        put("legalDetails.ogrnip", new Input(this, "legalDetails.ogrnip"));
        put("legalDetails.certificateNumber", new Input(this, "legalDetails.certificateNumber"));
        put("legalDetails.certificateDate", new Input(this, "legalDetails.certificateDate"));

        put("legalDetails.legalAddress.yur", new Textarea(this, By.cssSelector("fieldset[rel='legalEntity'] [name='legalDetails.legalAddress']")));
        put("legalDetails.inn.yur", new Input(this, By.cssSelector("fieldset[rel='legalEntity'] [name='legalDetails.inn']")));
        put("legalDetails.okpo.yur", new Input(this, By.cssSelector("fieldset[rel='legalEntity'] [name='legalDetails.okpo']")));
        put("legalDetails.kpp", new Input(this, "legalDetails.kpp"));
        put("legalDetails.ogrn", new Input(this, "legalDetails.ogrn"));
    }

    public void saveButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
        new PreLoader(getDriver()).await();
    }
}
