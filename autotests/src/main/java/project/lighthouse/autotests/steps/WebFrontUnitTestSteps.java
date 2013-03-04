package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.WebFrontUnitTest;

public class WebFrontUnitTestSteps extends ScenarioSteps{

    WebFrontUnitTest webFrontUnitTest;

    public WebFrontUnitTestSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void OpenUnitTestPage(){
        webFrontUnitTest.UnitTestPageOpen();
    }

    @Step
    public void CheckUTIsPassed(){
        webFrontUnitTest.CheckUTIsPassed();
    }
}
