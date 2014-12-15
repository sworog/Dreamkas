package ru.dreamkas.pos.model.api;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown=true)
public class SaleApiObject extends NamedObject{
    private PaymentApiObject payment;

    public SaleApiObject() {
        super();
    }

    public PaymentApiObject getPayment() {
        return payment;
    }
}
