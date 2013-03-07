package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.CommonSteps;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    @Then("the user checks that he is on the '$pageObjectName'")
    public void TheTheUserChecksThatHeIsOnTheProductListPage(String pageObjectName){
        commonSteps.CheckTheRequiredPageIsOpen(pageObjectName);
    }


}
