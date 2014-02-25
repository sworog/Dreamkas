package project.lighthouse.autotests.pages.commercialManager.catalog;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.InputOnlyVisible;

public class SubCategoryPage extends GroupPage {

    public SubCategoryPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("name", new InputOnlyVisible(this, "name"));
    }

    @Override
    public void addNewButtonClick() {
        new ButtonFacade(this, "Добавить подкатегорию").click();
    }

    @Override
    public String getItemXpath(String name) {
        String groupXpath = "//*[@model='catalogSubCategory' and text()='%s']";
        return String.format(groupXpath, name);
    }

    @Override
    public CommonItem getItem() {
        return items.get("name");
    }
}
