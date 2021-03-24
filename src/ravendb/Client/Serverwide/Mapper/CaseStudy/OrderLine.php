<?php
namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;

use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderLine {
    #[SerializedName("Id")]
    public string $id;

    public Comment $comment;
}