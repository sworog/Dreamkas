package project.lighthouse.autotests.pages.commercialManager.oraganization;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.Textarea;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

import java.util.Map;

@DefaultUrl("/company/organizations/create")
public class OrganizationFormPage extends CommonPageObject {

    public OrganizationFormPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Input(this, "name"));
        put("phone", new Input(this, "phone"));
        put("fax", new Input(this, "fax"));
        put("email", new Input(this, "email"));
        put("director", new Input(this, "director"));
        put("chiefAccountant", new Input(this, "chiefAccountant"));
        put("address", new Textarea(this, "address"));
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
