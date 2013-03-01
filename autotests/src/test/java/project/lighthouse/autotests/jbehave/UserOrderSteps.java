package project.lighthouse.autotests.jbehave;

import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;

import net.thucydides.core.annotations.Steps;
import project.lighthouse.autotests.steps.OrderSteps;

public class UserOrderSteps {
	
	@Steps
	OrderSteps orderSteps;
	
	@Given("the user is on the order create page")
    public void GivenTheUserIsOnTheOrderCreatePage() {
        orderSteps.IstheOrderCreatePage();
    }
	
	@Given("the user is on the order edit page")
	public void GivenTheUserIsOnTheOrderEditPage(){
		orderSteps.IsTheOrderEditPage();
	}
	@Given("the user is on the order card view")
	public void GivenTheUserIsOnTheOrderCardView(){
		orderSteps.IsTheOrderCardViewPage();
	}

    @When("the user inputs '$inputText' in '$elementName' field")
    public void WhenTheUserInputsTextInTheField(String inputText, String elementName) {
        orderSteps.FieldInput(elementName, inputText);
    }
    
    @When("the user selects '$value' in '$elementName' dropdown")
    public void WhenTheUserSelectsValueInDropDown(String value, String elementName){
    	orderSteps.SelectDropdown(elementName, value);
    }
    
    @When("the user edits '$inputText' in '$elementName' field")
    public void WhenTheUserEditsTextInTheField(String inputText, String elementName) {
        orderSteps.FieldEdit(elementName, inputText);
    }
    
    @When("the user clicks the create button")
    public void WhenTheUserClicksOnCreateButton(){
    	orderSteps.CreateButtonClick();
    }
    
    @When("the user clicks the edit button")
    public void WhenTheUserClicksOnEditButton(){
    	orderSteps.EditButtonClick();
    }
    
    @When("the user clicks the cancel button")
    public void WhenTheUserClickCancelEditButton(){
    	orderSteps.CancelButtonClick();
    }
    
    @Then("the user checks '$elementName' value is '$expectedValue'")
    public void ThenTheUserChecksValue(String elementName, String expectedValue){
    	orderSteps.CheckValue(elementName, expectedValue);
    }
    
    @Then("the user checks is all good")
    public void ThenTheUserChecksIsAllGood(){
    	//checks
    }    
    
    /*@Then("they should see the definition '$definition'")
    public void thenTheyShouldSeeADefinitionContainingTheWords(String definition) {
    }*/	

}
