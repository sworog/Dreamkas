package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.CommonSteps;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    @Then("the user checks that he is on the '$pageObjectName'")
    public void TheTheUserChecksThatHeIsOnTheProductListPage(String pageObjectName){
        commonSteps.CheckTheRequiredPageIsOpen(pageObjectName);
    }

    @When("The user generates test123 data with chars number equals ''")
    public void WhenTheUserGeneratesTestDataWithCharsNumber(int charNumber){

    }


}
