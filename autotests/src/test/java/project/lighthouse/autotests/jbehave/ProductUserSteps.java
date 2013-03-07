package project.lighthouse.autotests.jbehave;

import org.jbehave.core.annotations.*;

import net.thucydides.core.annotations.Steps;
import project.lighthouse.autotests.steps.ProductSteps;

public class ProductUserSteps {
	
	@Steps
	ProductSteps productSteps;
	
	@Given("the user is on the order create page")
    public void GivenTheUserIsOnTheOrderCreatePage() {
        productSteps.IsTheProductCreatePage();
    }
	
	@Given("the user is on the order edit page")
	public void GivenTheUserIsOnTheOrderEditPage(){
		productSteps.IsTheProductEditPage();
	}
	@Given("the user is on the order card view")
	public void GivenTheUserIsOnTheOrderCardView(){
		productSteps.IsTheProductCardViewPage();
	}

    @Given("the user is on the product list page")
    public void GivenTheUserIsOnTheProductListPage(){
        productSteps.IsTheProductListPageOpen();
    }

    @Given("the user is on the product card")
    public void GivenTheUserIsOnTheProductCard(){
        productSteps.IsTheProductCardOpen();
    }

    @When("the user inputs '$inputText' in '$elementName' field")
    public void WhenTheUserInputsTextInTheField(String inputText, String elementName) {
        productSteps.FieldInput(elementName, inputText);
    }
    
    @When("the user selects '$value' in '$elementName' dropdown")
    public void WhenTheUserSelectsValueInDropDown(String value, String elementName){
    	productSteps.SelectDropdown(elementName, value);
    }
    
    @When("the user edits '$inputText' in '$elementName' field")
    public void WhenTheUserEditsTextInTheField(String inputText, String elementName) {
        productSteps.FieldEdit(elementName, inputText);
    }
    
    @When("the user clicks the create button")
    public void WhenTheUserClicksOnCreateButton(){
    	productSteps.CreateButtonClick();
    }
    
    @When("the user clicks the edit button")
    public void WhenTheUserClicksOnEditButton(){
    	productSteps.CreateButtonClick();
    }
    
    @When("the user clicks the cancel button")
    public void WhenTheUserClickCancelEditButton(){
    	productSteps.CancelButtonClick();
    }

    @When("the user creates new product from product list page")
    public void WhenTheUSerCreatesNewProduct(){
        productSteps.CreateNewProductButtonClick();
    }

    @When("the user open the product card with '$skuValue' sku")
    public void WhenTheUserOpenTheProductCard(String skuValue){
        productSteps.ListItemClick(skuValue);
    }

    
    @Then("the user checks the '$elementName' value is '$expectedValue'")
    public void ThenTheUserChecksValue(String elementName, String expectedValue){
    	productSteps.CheckValue(elementName, expectedValue);
    }
    
    @Then("the user checks is all good")
    public void ThenTheUserChecksIsAllGood(){
    	//checks
    }

    @Then("the user checks the product with '$skuValue' sku is present")
    public void ThenTheUSerChecksTheProductWithSkuIsPresent(String skuValue){
        productSteps.ListItemCheck(skuValue);
    }

    @Then("the user checks the product with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void CheckProductWithSkuHasExpectedValue(String skuValue, String expectedValue){
        productSteps.CheckProductWithSkuHasExpectedValue(skuValue, expectedValue);
    }

    @Then("the user checks default value for '$dropdownType' dropdown equal to '$expectedValue'")
    public void ThenTheUSerChecksDefaultValueForDropDown(String dropdownType, String expectedValue){
        productSteps.CheckDropDownDefaultValue(dropdownType, expectedValue);
    }

    @Then("the user checks that he is on the product list page")
    public void TheTheUserChecksThatHeIsOnTheProductListPage(){
        productSteps.CheckTheRequiredPageIsProductListPage();
    }
}
