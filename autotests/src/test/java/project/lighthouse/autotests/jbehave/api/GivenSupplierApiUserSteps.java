package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.supplier.SupplierApiSteps;

import java.io.IOException;

public class GivenSupplierApiUserSteps {

    @Steps
    SupplierApiSteps supplierApiSteps;

    @Given("the user with email '$userEmail' creates supplier with name '$name', address '$address', phone '$phone', email '$email', contactPerson '$contactPerson'")
    @Alias("пользователь с адресом электронной почты '$userEmail' создает поставщика с именем '$name', адресом '$address', телефоном '$phone', почтой '$email' и контактным лицом '$contactPerson'")
    public void givenTheUserWithEmailCreatesSupplier(String userEmail,
                                                     String name,
                                                     String address,
                                                     String phone,
                                                     String email,
                                                     String contactPerson) throws IOException, JSONException {
        supplierApiSteps.createSupplierByUserWithEmail(userEmail, name, address, phone, email, contactPerson);
    }
}
