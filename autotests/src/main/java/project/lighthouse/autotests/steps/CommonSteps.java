package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.common.ICommonPage;

public class CommonSteps extends ScenarioSteps{

    ICommonPage commonPage;

    public CommonSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void CheckTheRequiredPageIsOpen(String pageObjectName){
        commonPage.isRequiredPageOpen(pageObjectName);
    }

    @Step
    public void CheckErrorMessages(ExamplesTable errorMessageTable){
        commonPage.CheckErrorMessages(errorMessageTable);
    }

    @Step
    public void CheckNoErrorMessages(){
        commonPage.CheckNoErrorMessages();
    }
}
