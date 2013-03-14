package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.CommonPage;
import project.lighthouse.autotests.pages.ProductCreatePage;

public class CommonSteps extends ScenarioSteps{

    CommonPage commonPage;

    public CommonSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void CheckTheRequiredPageIsOpen(String pageObjectName){
        commonPage.isRequiredPageOpen(pageObjectName);
    }
}
