package project.lighthouse.autotests.pages;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class WebFrontUnitTest extends PageObject{

    @FindBy(xpath = "//a[@wc_test_passed='true']")
    private WebElement testMarker;

    private String UTPage = "http://localhost:8008/mayak/-mix/index.stage=release.doc.xml";

    public WebFrontUnitTest(WebDriver driver) {
        super(driver);
    }

    public void CheckUTIsPassed(){
        $(testMarker).shouldBePresent();
    }

    public void UnitTestPageOpen(){
        getDriver().navigate().to(UTPage);
    }
}
