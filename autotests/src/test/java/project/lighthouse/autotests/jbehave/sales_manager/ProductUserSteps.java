package project.lighthouse.autotests.jbehave.sales_manager;

import org.jbehave.core.annotations.*;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.ProductSteps;

import javax.persistence.Table;

public class ProductUserSteps {
	
	@Steps
	ProductSteps productSteps;
	
	@Given("the user is on the product create page")
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

    @Given("there is created product with sku '$sku'")
    public void GivenThereIsCreatedProductWithSku(String sku){
        GivenTheUserIsOnTheProductListPage();
        WhenTheUSerCreatesNewProduct();
        WhenTheUserInputsTextInTheField(sku, "sku");
        WhenTheUserInputsTextInTheField(sku, "name");
        WhenTheUserInputsTextInTheField("123", "purchasePrice");
        WhenTheUserSelectsValueInDropDown("unit", "unit");
        WhenTheUserSelectsValueInDropDown("10", "vat");
        WhenTheUserClicksOnCreateButton();
    }

    @When("the user inputs '$inputText' in '$elementName' field")
    public void WhenTheUserInputsTextInTheField(String inputText, String elementName) {
        productSteps.FieldInput(elementName, inputText);
    }

    @When("the user inputs values in element fields $fieldInputTable")
    public void WhenTheUserInputsInElementFields(ExamplesTable fieldInputTable){
        productSteps.FieldType(fieldInputTable);
    }
    
    @When("the user selects '$value' in '$elementName' dropdown")
    public void WhenTheUserSelectsValueInDropDown(String value, String elementName){
    	productSteps.SelectDropDown(elementName, value);
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

    @When("the user clicks the edit button on product card view page")
    public void WhenTheUserClicksTheEditButtonOnProductCardViewPage(){
        productSteps.EditButtonClick();
    }

    @When("the user generates charData with '$charNumber' number in the '$elementName' field")
    public void WhenTheUserGeneratesCharData(String elementName, int charNumber){
        productSteps.GenerateTestCharData(elementName, charNumber);
    }
    
    @Then("the user checks the '$elementName' value is '$expectedValue'")
    public void ThenTheUserChecksValue(String elementName, String expectedValue){
    	productSteps.CheckCardValue(elementName, expectedValue);
    }

    @Then("the user checks the elements values $checkValuesTable")
    public void ThenTheUserChecksTheElementValues(ExamplesTable checkValuesTable){
        productSteps.CheckCardValue(checkValuesTable);
    }

    @Then("the user checks the product with '$skuValue' sku is present")
    public void ThenTheUSerChecksTheProductWithSkuIsPresent(String skuValue){
        productSteps.ListItemCheck(skuValue);
    }

    @Then("the user checks the product with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void CheckProductWithSkuHasExpectedValue(String skuValue, String expectedValue){
        productSteps.CheckProductWithSkuHasExpectedValue(skuValue, expectedValue);
    }

    @Then("the user checks default value for '$dropDownType' dropdown equal to '$expectedValue'")
    public void ThenTheUSerChecksDefaultValueForDropDown(String dropDownType, String expectedValue){
        productSteps.CheckDropDownDefaultValue(dropDownType, expectedValue);
    }

    @Then("the user checks '$elementName' field contains only '$fieldLength' symbols")
    public void ThenTheUserChecksNameFieldContainsOnlyExactSymbols(String elementName, int fieldLength){
        productSteps.CheckFieldLength(elementName, fieldLength);
    }

    @Then("the user sees error messages $errorMessageTable")
    public void ThenTheUserSeesErrorMessages(ExamplesTable errorMessageTable){
        productSteps.CheckErrorMessages(errorMessageTable);
    }

    @Then("the user sees no error messages")
    public void ThenTheUserSeesNoErrorMessages(){
        productSteps.CheckNoErrorMessages();
    }
}
