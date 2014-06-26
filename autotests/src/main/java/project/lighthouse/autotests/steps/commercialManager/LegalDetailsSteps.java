package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.commercialManager.legalDetails.LegalDetailsFormPage;

public class LegalDetailsSteps extends ScenarioSteps {
    LegalDetailsFormPage legalDetailsFormPage;

    @Step
    public void selectLegalDetailsType(String value) {
        legalDetailsFormPage.click(By.xpath("//span[normalize-space(text())=\"" + value + "\"]"));
    }

    @Step
    public void fillInputs(ExamplesTable data) {
        legalDetailsFormPage.fieldInput(data);
    }

    @Step
    public void clickSaveButton() {
        legalDetailsFormPage.saveButtonClick();
    }

    @Step
    public void checkLegalDetailsData(ExamplesTable data) {
        legalDetailsFormPage.checkValues(data);
    }
}
